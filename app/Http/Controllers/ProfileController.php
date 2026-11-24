<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

/**
 * La cuenta del usuario de la sesion: ver y editar lo suyo.
 *
 * No usa el AuthorizedMiddleware: cualquier usuario autenticado puede ver
 * y cambiar SU perfil. Por eso nunca se recibe un id por la URL ni por el
 * formulario: siempre se trabaja sobre Auth::user().
 */
class ProfileController extends Controller
{
    public function show() {

        $user = Auth::user();

        return view('profile/show', [
            'user' => $user,
            'role' => $user->role?->name ?? 'Sin rol',
        ]);
    }

    public function update(Request $request) {

        $user = Auth::user();

        Validator::make($request->all(),
        [
            'name'  => 'required|max:120',
            // El id al final: el correo no puede chocar consigo mismo
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ],
        [
            'name.required'  => 'El nombre es requerido',
            'name.max'       => 'El nombre no puede tener más de :max caracteres',
            'email.required' => 'El correo es requerido',
            'email.email'    => 'El correo no es válido',
            'email.max'      => 'El correo no puede tener más de :max caracteres',
            'email.unique'   => 'Ya existe un usuario con ese correo',
        ])->validate();

        try {

            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            Session::flash('message', [ 'content' => "Perfil actualizado con éxito", 'type' => 'success' ]);
            return redirect()->route('profile.show');

        } catch(Exception $ex) {

            Log::error($ex);
            Session::flash('message', [ 'content' => "Ha ocurrido un error.", 'type' => 'error' ]);
            return redirect()->back();
        }
    }

    public function password() {

        return view('profile/password');
    }

    public function updatePassword(Request $request) {

        $user = Auth::user();

        $validator = Validator::make($request->all(),
        [
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ],
        [
            'current_password.required' => 'La contraseña actual es requerida',
            'password.required'         => 'La nueva contraseña es requerida',
            'password.min'              => 'La nueva contraseña debe tener al menos :min caracteres',
            'password.confirmed'        => 'La confirmación no coincide con la nueva contraseña',
        ]);

        // Antes de cambiarla, la actual tiene que ser correcta: si alguien
        // encuentra la sesion abierta, no puede cambiar la contraseña.
        $validator->after(function ($validator) use ($request, $user) {

            if (!empty($request->current_password)
                && !Hash::check($request->current_password, $user->password)) {

                $validator->errors()->add('current_password', 'La contraseña actual no es correcta');
            }
        });

        $validator->validate();

        try {

            $user->password = Hash::make($request->password);
            $user->save();

            Session::flash('message', [ 'content' => "Contraseña cambiada con éxito", 'type' => 'success' ]);
            return redirect()->route('profile.show');

        } catch(Exception $ex) {

            Log::error($ex);
            Session::flash('message', [ 'content' => "Ha ocurrido un error.", 'type' => 'error' ]);
            return redirect()->back();
        }
    }
}
