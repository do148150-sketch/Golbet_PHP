<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\GameMatch;
use App\Models\Team;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MatchesController extends Controller
{
    /**
     * Cartelera de partidos, con filtro por estado y por equipo.
     *
     * Los dos filtros viajan por GET, asi que la cartelera se puede
     * recargar y compartir tal como se esta viendo.
     */
    public function index(Request $request) {

        $status = $request->input('status');
        $teamId = $request->input('team_id');

        // Solo se aceptan estados de la lista; cualquier otra cosa se ignora
        if (!in_array($status, GameMatch::STATUSES, true)) {
            $status = null;
        }

        // with() trae los equipos en la misma consulta: sin esto, la vista
        // dispararia dos consultas por cada fila de la cartelera.
        $query = GameMatch::with(['homeTeam', 'awayTeam'])
                          ->where('is_active', true);

        if ($status !== null) {
            $query->where('status', $status);
        }

        if (!empty($teamId)) {

            // El equipo puede jugar de local o de visitante
            $query->where(function ($q) use ($teamId) {
                $q->where('home_team_id', $teamId)
                  ->orWhere('away_team_id', $teamId);
            });
        }

        $matches = $query->orderBy('match_date')->get();

        return view('matches/index', [
            'matches'  => $matches,
            'teams'    => Team::where('is_active', true)->orderBy('name')->get(),
            'statuses' => GameMatch::STATUSES,
            'status'   => $status,
            'teamId'   => $teamId,
        ]);
    }

    /**
     * Detalle del partido: cuotas, marcador si ya terminó, y el total apostado.
     */
    public function show($id) {

        $match = GameMatch::with(['homeTeam', 'awayTeam'])->find($id);

        if (empty($match)) {

            Session::flash('message', [ 'content' => "El partido con id '$id' no existe.", 'type' => 'error' ]);
            return redirect()->route('matches.index');
        }

        return view('matches/show', [
            'match'     => $match,
            'totalBets' => $match->bets()->count(),
        ]);
    }

    public function create() {

        return view('matches/create', [
            'teams' => Team::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function edit($id) {

        $match = GameMatch::find($id);

        if (empty($match)) {

            Session::flash('message', [ 'content' => "El partido con id '$id' no existe.", 'type' => 'error' ]);
            return redirect()->back();
        }

        return view('matches/edit', [
            'match' => $match,
            'teams' => Team::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request) {

        $this->validateMatch($request);

        try {

            $match = new GameMatch();
            $this->fill($match, $request);
            $match->status = GameMatch::SCHEDULED;
            $match->save();

            Session::flash('message', [ 'content' => "Partido creado con éxito", 'type' => 'success' ]);
            return redirect()->route('matches.index');

        } catch(Exception $ex) {

            Log::error($ex);
            Session::flash('message', [ 'content' => "Ha ocurrido un error.", 'type' => 'error' ]);
            return redirect()->back();
        }
    }

    /**
     * Editar el partido. Aqui tambien se actualizan las cuotas: son los
     * mismos tres campos del formulario.
     */
    public function update(Request $request) {

        // Primero el id: sin saber que partido es, no se puede decidir
        // si todavia hay que exigirle fecha futura.
        Validator::make($request->all(),
        [
            'match_id' => 'required|exists:matches,id',
        ],
        [
            'match_id.required' => 'El partido es requerido',
            'match_id.exists'   => 'El id dado no existe',
        ])->validate();

        $match = GameMatch::find($request->match_id);

        // Un partido cerrado ya resolvió sus apuestas: tocarle las cuotas
        // o la fecha dejaría los pagos sin respaldo.
        if ($match->status === GameMatch::FINISHED) {

            Session::flash('message', [ 'content' => "Un partido finalizado no se puede editar.", 'type' => 'error' ]);
            return redirect()->route('matches.index');
        }

        $this->validateMatch($request, $match);

        try {

            $this->fill($match, $request);
            $match->save();

            Session::flash('message', [ 'content' => "Partido actualizado con éxito", 'type' => 'success' ]);
            return redirect()->route('matches.index');

        } catch(Exception $ex) {

            Log::error($ex);
            Session::flash('message', [ 'content' => "Ha ocurrido un error.", 'type' => 'error' ]);
            return redirect()->back();
        }
    }

    public function delete($id) {

        try {

            $match = GameMatch::find($id);

            if (empty($match)) {

                Session::flash('message', [ 'content' => "El partido con id '$id' no existe.", 'type' => 'error' ]);
                return redirect()->back();
            }

            // No se borra un partido que ya tiene gente apostando:
            // esas apuestas quedarian huerfanas.
            $pending = $match->bets()->count();

            if ($pending > 0) {

                Session::flash('message', [
                    'content' => "No se puede eliminar: el partido ya tiene $pending apuesta(s).",
                    'type'    => 'error',
                ]);
                return redirect()->route('matches.index');
            }

            // Borrado logico, igual que en equipos
            $match->is_active = false;
            $match->save();

            Session::flash('message', [ 'content' => "Partido eliminado con éxito", 'type' => 'success' ]);
            return redirect()->route('matches.index');

        } catch(Exception $ex) {

            Log::error($ex);
            Session::flash('message', [ 'content' => "Ha ocurrido un error.", 'type' => 'error' ]);
            return redirect()->back();
        }
    }

    /**
     * Formulario para registrar el marcador final y cerrar el partido.
     */
    public function closeForm($id) {

        $match = GameMatch::with(['homeTeam', 'awayTeam'])->find($id);

        if (empty($match) || !$match->is_active) {

            Session::flash('message', [ 'content' => "El partido con id '$id' no existe.", 'type' => 'error' ]);
            return redirect()->route('matches.index');
        }

        if ($match->status === GameMatch::FINISHED) {

            Session::flash('message', [ 'content' => "Este partido ya fue cerrado y sus apuestas resueltas.", 'type' => 'error' ]);
            return redirect()->route('matches.show', $match->id);
        }

        return view('matches/close', [
            'match'       => $match,
            'pendingBets' => $match->bets()->where('status', Bet::PENDING)->count(),
        ]);
    }

    /**
     * Registra el marcador final, cierra el partido y resuelve sus apuestas.
     *
     * Todo ocurre dentro de una transaccion: si algo falla a mitad de camino,
     * no puede quedar el partido cerrado con la mitad de las apuestas sin pagar.
     */
    public function close(Request $request) {

        Validator::make($request->all(),
        [
            'match_id'   => 'required|exists:matches,id',
            'home_goals' => 'required|integer|min:0|max:99',
            'away_goals' => 'required|integer|min:0|max:99',
        ],
        [
            'match_id.required'   => 'El partido es requerido',
            'match_id.exists'     => 'El id dado no existe',
            'home_goals.required' => 'Los goles del local son requeridos',
            'home_goals.integer'  => 'Los goles del local deben ser un número entero',
            'home_goals.min'      => 'Los goles del local no pueden ser negativos',
            'home_goals.max'      => 'Los goles del local no pueden ser más de :max',
            'away_goals.required' => 'Los goles del visitante son requeridos',
            'away_goals.integer'  => 'Los goles del visitante deben ser un número entero',
            'away_goals.min'      => 'Los goles del visitante no pueden ser negativos',
            'away_goals.max'      => 'Los goles del visitante no pueden ser más de :max',
        ])->validate();

        $homeGoals = (int) $request->home_goals;
        $awayGoals = (int) $request->away_goals;

        try {

            $summary = DB::transaction(function () use ($request, $homeGoals, $awayGoals) {

                // lockForUpdate: si dos administradores cierran al mismo tiempo,
                // el segundo espera y despues ve el partido ya finalizado.
                $match = GameMatch::lockForUpdate()->find($request->match_id);

                // 1. Primer cinturon contra el doble pago: un partido
                //    finalizado ya resolvio sus apuestas.
                if ($match->status === GameMatch::FINISHED) {
                    throw new Exception('Este partido ya fue cerrado y sus apuestas resueltas.');
                }

                if (!$match->is_active) {
                    throw new Exception('El partido no existe.');
                }

                // 2. Marcador y estado final
                $match->home_goals = $homeGoals;
                $match->away_goals = $awayGoals;
                $match->status     = GameMatch::FINISHED;
                $match->save();

                // 3. Cual de los tres resultados gano
                if ($homeGoals > $awayGoals) {
                    $winner = Bet::HOME;
                } elseif ($homeGoals < $awayGoals) {
                    $winner = Bet::AWAY;
                } else {
                    $winner = Bet::DRAW;
                }

                // 4. Segundo cinturon: solo las apuestas PENDIENTES de este
                //    partido. Una apuesta ya resuelta no vuelve a entrar.
                $bets = Bet::where('match_id', '=', $match->id)
                           ->where('status', '=', Bet::PENDING)
                           ->with('user')
                           ->get();

                $won = 0;
                $paid = 0;

                foreach ($bets as $bet) {

                    if ($bet->pick === $winner) {

                        // Paga la cuota CONGELADA al apostar, no la actual del partido
                        $payout = $bet->potentialPayout();

                        $bet->status = Bet::WON;
                        $bet->user->balance += $payout;
                        $bet->user->save();

                        $won++;
                        $paid += $payout;

                    } else {

                        // El monto ya se desconto al apostar: no hay nada que cobrar
                        $bet->status = Bet::LOST;
                    }

                    $bet->save();
                }

                return [
                    'resolved' => $bets->count(),
                    'won'      => $won,
                    'paid'     => $paid,
                ];
            });

            $paid = number_format($summary['paid'], 0, ',', '.');

            Session::flash('message', [
                'content' => "Partido cerrado {$homeGoals} - {$awayGoals}. "
                           . "Apuestas resueltas: {$summary['resolved']}. "
                           . "Ganadoras: {$summary['won']}. "
                           . "FutCoins pagados: {$paid}.",
                'type'    => 'success',
            ]);

            return redirect()->route('matches.show', $request->match_id);

        } catch(Exception $ex) {

            Log::error($ex);

            // Los mensajes de las guardas se le muestran tal cual al administrador
            $content = in_array($ex->getMessage(), [
                'Este partido ya fue cerrado y sus apuestas resueltas.',
                'El partido no existe.',
            ], true) ? $ex->getMessage() : "Ha ocurrido un error.";

            Session::flash('message', [ 'content' => $content, 'type' => 'error' ]);
            return redirect()->route('matches.show', $request->match_id);
        }
    }

    // ---------------------------------------------------------------
    // Ayudas privadas
    // ---------------------------------------------------------------

    private function fill(GameMatch $match, Request $request): void {

        $match->home_team_id = $request->home_team_id;
        $match->away_team_id = $request->away_team_id;
        $match->match_date   = Carbon::createFromFormat('Y-m-d H:i', "$request->match_date $request->match_time");
        $match->home_odds    = $request->home_odds;
        $match->draw_odds    = $request->draw_odds;
        $match->away_odds    = $request->away_odds;
    }

    /**
     * Crear y editar validan los mismos campos, asi que las reglas
     * viven en un solo sitio.
     *
     * $match es null al crear, y el partido existente al editar: sirve para
     * saber si todavia hay que exigir que la fecha sea futura.
     */
    private function validateMatch(Request $request, ?GameMatch $match = null): void {

        $validator = Validator::make($request->all(),
        [
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id',
            'match_date'   => 'required|date_format:Y-m-d',
            'match_time'   => 'required|date_format:H:i',
            'home_odds'    => 'required|numeric|min:1.01|max:99.99',
            'draw_odds'    => 'required|numeric|min:1.01|max:99.99',
            'away_odds'    => 'required|numeric|min:1.01|max:99.99',
        ],
        [
            'home_team_id.required' => 'El equipo local es requerido',
            'home_team_id.exists'   => 'El equipo local no existe',
            'away_team_id.required' => 'El equipo visitante es requerido',
            'away_team_id.exists'   => 'El equipo visitante no existe',
            'match_date.required'   => 'La fecha del partido es requerida',
            'match_date.date_format'=> 'La fecha del partido no es válida',
            'match_time.required'   => 'La hora del partido es requerida',
            'match_time.date_format'=> 'La hora del partido no es válida',
            'home_odds.required'    => 'La cuota local es requerida',
            'home_odds.numeric'     => 'La cuota local debe ser un número',
            'home_odds.min'         => 'La cuota local debe ser mayor a :min',
            'home_odds.max'         => 'La cuota local no puede ser mayor a :max',
            'draw_odds.required'    => 'La cuota de empate es requerida',
            'draw_odds.numeric'     => 'La cuota de empate debe ser un número',
            'draw_odds.min'         => 'La cuota de empate debe ser mayor a :min',
            'draw_odds.max'         => 'La cuota de empate no puede ser mayor a :max',
            'away_odds.required'    => 'La cuota visitante es requerida',
            'away_odds.numeric'     => 'La cuota visitante debe ser un número',
            'away_odds.min'         => 'La cuota visitante debe ser mayor a :min',
            'away_odds.max'         => 'La cuota visitante no puede ser mayor a :max',
        ]);

        // Reglas de negocio del enunciado: las que no sabe expresar el
        // validador por si solo. after() las suma a los mismos errores,
        // asi que salen en la misma alerta que las demas.
        $validator->after(function ($validator) use ($request, $match) {

            if ($request->home_team_id === $request->away_team_id) {

                $validator->errors()->add(
                    'away_team_id',
                    'El equipo local y el visitante no pueden ser el mismo.'
                );
            }

            // Si la fecha u hora venian mal, Carbon no las puede leer:
            // ese error ya lo reportaron las reglas de arriba.
            if ($validator->errors()->hasAny(['match_date', 'match_time'])) {
                return;
            }

            $date = Carbon::createFromFormat('Y-m-d H:i', "$request->match_date $request->match_time");

            // Un partido nuevo siempre va al futuro. A uno que ya empezo no
            // se le exige, porque su fecha ya paso por definicion.
            $todaviaProgramado = $match === null || $match->status === GameMatch::SCHEDULED;

            if ($todaviaProgramado && $date->isPast()) {

                $validator->errors()->add(
                    'match_date',
                    'La fecha del partido debe ser futura.'
                );
            }
        });

        $validator->validate();
    }
}
