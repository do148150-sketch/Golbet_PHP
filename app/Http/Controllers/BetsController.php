<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\GameMatch;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BetsController extends Controller
{
    /**
     * Mis apuestas: solo las del usuario de la sesion, las mas recientes arriba.
     */
    public function index() {

        // with(): trae los partidos y sus equipos en pocas consultas,
        // en vez de hacer una consulta por cada fila dentro de la vista.
        $bets = Bet::with(['match.homeTeam', 'match.awayTeam'])
                   ->where('user_id', Auth::id())
                   ->orderBy('created_at', 'desc')
                   ->get();

        return view('bets/index', [ 'bets' => $bets ]);
    }

    public function show($id) {

        $bet = Bet::with(['match.homeTeam', 'match.awayTeam'])->find($id);

        if (empty($bet)) {

            Session::flash('message', [ 'content' => "La apuesta con id '$id' no existe.", 'type' => 'error' ]);
            return redirect()->route('bets.index');
        }

        // Cada quien solo ve SUS apuestas. Sin esto, cambiando el
        // numero en la URL cualquiera podria ver las apuestas ajenas.
        if ($bet->user_id !== Auth::id()) {

            abort(403, 'Esta apuesta no es tuya.');
        }

        return view('bets/show', [ 'bet' => $bet ]);
    }

    public function store(Request $request) {

        Validator::make($request->all(),
        [
            'match_id' => 'required|exists:matches,id',
            'pick' => 'required|in:' . implode(',', Bet::PICKS),
            'amount' => 'required|numeric|min:1'
        ],
        [
            'match_id.required' => 'El partido es requerido',
            'match_id.exists' => 'El partido no existe',
            'pick.required' => 'Debe elegir un pronóstico',
            'pick.in' => 'El pronóstico no es válido',
            'amount.required' => 'El monto es requerido',
            'amount.numeric' => 'El monto debe ser un número',
            'amount.min' => 'El monto debe ser de al menos :min FutCoin'
        ])->validate();

        $match = GameMatch::find($request->match_id);
        $user = Auth::user();

        // Regla 1: el partido tiene que admitir apuestas.
        // Se vuelve a comprobar aqui aunque el formulario este oculto en la vista.
        if (!$match->acceptsBets()) {

            Session::flash('message', [ 'content' => "Este partido ya no admite apuestas.", 'type' => 'error' ]);
            return redirect()->route('matches.show', $match->id);
        }

        // Regla 2: no se puede apostar mas de lo que se tiene
        if ($request->amount > $user->balance) {

            $saldo = number_format($user->balance, 0, ',', '.');
            $monto = number_format($request->amount, 0, ',', '.');

            Session::flash('message', [ 'content' => "Saldo insuficiente: tienes $saldo FutCoins e intentaste apostar $monto.", 'type' => 'error' ]);
            return redirect()->back()->withInput();
        }

        try {

            // Crear la apuesta y descontar el saldo ocurren juntos o no ocurren:
            // si algo falla a mitad de camino, la transaccion deshace todo.
            $bet = DB::transaction(function () use ($request, $match, $user) {

                $bet = new Bet();
                $bet->user_id = $user->id;
                $bet->match_id = $match->id;
                $bet->amount = $request->amount;
                $bet->pick = $request->pick;

                // Regla 3: la cuota se CONGELA. Se guarda la del pronostico en
                // este momento; si el administrador la cambia despues, esta
                // apuesta sigue pagando la que vio el apostador.
                $bet->odds_at_placement = match ($request->pick) {
                    Bet::HOME => $match->home_odds,
                    Bet::DRAW => $match->draw_odds,
                    Bet::AWAY => $match->away_odds,
                };

                $bet->status = Bet::PENDING;
                $bet->save();

                // Regla 4: el saldo se descuenta al apostar, no al resolver
                $user->balance -= $request->amount;
                $user->save();

                return $bet;
            });

            Session::flash('message', [ 'content' => "Apuesta realizada con éxito", 'type' => 'success' ]);
            return redirect()->route('bets.show', $bet->id);

        } catch(Exception $ex) {

            Log::error($ex);
            Session::flash('message', [ 'content' => "Ha ocurrido un error.", 'type' => 'error' ]);
            return redirect()->back();
        }
    }
}
