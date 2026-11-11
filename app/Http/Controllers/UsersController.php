<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index() {

        // Traemos los usuarios ya con el nombre de su rol (join con la tabla roles).
        // leftJoin: si algun usuario no tiene rol, igual aparece en la lista.
        $users = User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
                     ->select('users.id', 'users.name', 'users.email', 'roles.name as role_name')
                     ->orderBy('users.name')
                     ->get();

        return view('users/index', [ 'users' => $users ]);
    }

    public function create() {

        $roles = Role::all();

        return view('users/create', [ 'roles' => $roles ]);
    }

    public function edit($id) {

        $user = User::find($id);

        if (empty($user)) {

            Session::flash('message', [ 'content' => "El usuario con id '$id' no existe.", 'type' => 'error' ]);
            return redirect()->back();
        }

        $roles = Role::all();

        return view('users/edit', [ 'user' => $user, 'roles' => $roles ]);
    }

    public function store(Request $request) {

        Validator::make($request->all(),
        [
            'name' => 'required|max:120',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4',
            'role_id' => 'required|exists:roles,id'
        ],
        [
            'name.required' => 'El nombre es requerido',
            'name.max' => 'El nombre no puede ser mayor a :max caracteres',
            'email.required' => 'El correo es requerido',
            'email.email' => 'El correo no es válido',
            'email.unique' => 'Ya existe un usuario con ese correo',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe tener al menos :min caracteres',
            'role_id.required' => 'El rol es requerido',
            'role_id.exists' => 'El rol seleccionado no existe'
        ])->validate();

        try {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role_id = $request->role_id;
            $user->save();

            Session::flash('message', [ 'content' => "Usuario creado con éxito", 'type' => 'success' ]);
            return redirect()->route('users.index');

        } catch(Exception $ex) {

            Log::error($ex);
            Session::flash('message', [ 'content' => "Ha ocurrido un error.", 'type' => 'error' ]);
            return redirect()->back();
        }
    }

    public function update(Request $request) {

        Validator::make($request->all(),
        [
            'name' => 'required|max:120',
            'email' => 'required|email|unique:users,email,' . $request->user_id,
            'password' => 'nullable|min:4',
            'role_id' => 'required|exists:roles,id',
            'user_id' => 'required|exists:users,id'
        ],
        [
            'name.required' => 'El nombre es requerido',
            'name.max' => 'El nombre no puede ser mayor a :max caracteres',
            'email.required' => 'El correo es requerido',
            'email.email' => 'El correo no es válido',
            'email.unique' => 'Ya existe un usuario con ese correo',
            'password.min' => 'La contraseña debe tener al menos :min caracteres',
            'role_id.required' => 'El rol es requerido',
            'role_id.exists' => 'El rol seleccionado no existe',
            'user_id.required' => 'El usuario es requerido',
            'user_id.exists' => 'El id dado no existe'
        ])->validate();

        try {

            $user = User::find($request->user_id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role_id = $request->role_id;

            // La contraseña solo se cambia si escribieron una nueva.
            // Si el campo viene vacio, se deja la que ya tenia.
            if (!empty($request->password)) {

                $user->password = Hash::make($request->password);
            }

            $user->save();

            Session::flash('message', [ 'content' => "Usuario actualizado con éxito", 'type' => 'success' ]);
            return redirect()->route('users.index');

        } catch(Exception $ex) {

            Log::error($ex);
            Session::flash('message', [ 'content' => "Ha ocurrido un error.", 'type' => 'error' ]);
            return redirect()->back();
        }
    }
}
