<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TeamsController extends Controller
{
    public function index() {

        $teams = Team::where('is_active', true)->get();

        return view('teams/index', [ 'teams' => $teams ]);
    }

    public function create() {

        return view('teams/create');
    }

    public function edit($id) {

        $team = Team::find($id);

        if (empty($team)) {

            Session::flash('message', [ 'content' => "El equipo con id '$id' no existe.", 'type' => 'error' ]);
            return redirect()->back();
        }

        return view('teams/edit', [ 'team' => $team ]);
    }

    public function store(Request $request) {

        Validator::make($request->all(),
        [
            'name' => 'required|max:80|unique:teams,name',
            'city' => 'required|max:60',
            'crest_url' => 'nullable|url|max:300'
        ],
        [
            'name.required' => 'El nombre es requerido',
            'name.max' => 'El nombre no puede ser mayor a :max caracteres',
            'name.unique' => 'Ya existe un equipo con ese nombre',
            'city.required' => 'La ciudad es requerida',
            'city.max' => 'La ciudad no puede ser mayor a :max caracteres',
            'crest_url.url' => 'La URL del escudo no es válida',
            'crest_url.max' => 'La URL del escudo no puede ser mayor a :max caracteres'
        ])->validate();

        try {

            $team = new Team();
            $team->name = $request->name;
            $team->city = $request->city;
            $team->crest_url = $request->crest_url;
            $team->save();

            Session::flash('message', [ 'content' => "Equipo creado con éxito", 'type' => 'success' ]);
            return redirect()->route('teams.index');

        } catch(Exception $ex) {

            Log::error($ex);
            Session::flash('message', [ 'content' => "Ha ocurrido un error.", 'type' => 'error' ]);
            return redirect()->back();
        }
    }

    public function update(Request $request) {

        Validator::make($request->all(),
        [
            'name' => 'required|max:80|unique:teams,name,' . $request->team_id,
            'city' => 'required|max:60',
            'crest_url' => 'nullable|url|max:300',
            'team_id' => 'required|exists:teams,id'
        ],
        [
            'name.required' => 'El nombre es requerido',
            'name.max' => 'El nombre no puede ser mayor a :max caracteres',
            'name.unique' => 'Ya existe un equipo con ese nombre',
            'city.required' => 'La ciudad es requerida',
            'city.max' => 'La ciudad no puede ser mayor a :max caracteres',
            'crest_url.url' => 'La URL del escudo no es válida',
            'crest_url.max' => 'La URL del escudo no puede ser mayor a :max caracteres',
            'team_id.required' => 'El equipo es requerido',
            'team_id.exists' => 'El id dado no existe',
        ])->validate();

        try {

            $team = Team::find($request->team_id);
            $team->name = $request->name;
            $team->city = $request->city;
            $team->crest_url = $request->crest_url;
            $team->save();

            Session::flash('message', [ 'content' => "Equipo actualizado con éxito", 'type' => 'success' ]);
            return redirect()->route('teams.index');

        } catch(Exception $ex) {

            Log::error($ex);
            Session::flash('message', [ 'content' => "Ha ocurrido un error.", 'type' => 'error' ]);
            return redirect()->back();
        }
    }

    public function delete($id) {

        try {

            $team = Team::find($id);

            if (empty($team)) {

                Session::flash('message', [ 'content' => "El equipo con id '$id' no existe.", 'type' => 'error' ]);
                return redirect()->back();
            }

            // Borrado logico: el registro no se destruye, se desactiva.
            // El listado solo muestra los equipos con is_active = true.
            $team->is_active = false;
            $team->save();

            Session::flash('message', [ 'content' => "Equipo eliminado con éxito", 'type' => 'success' ]);
            return redirect()->route('teams.index');

        } catch(Exception $ex) {

            Log::error($ex);
            Session::flash('message', [ 'content' => "Ha ocurrido un error.", 'type' => 'error' ]);
            return redirect()->back();
        }
    }
}
