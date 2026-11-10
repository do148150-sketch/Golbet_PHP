<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function login() {

        return view('auth/login');
    }

    public function loginPost(Request $request) : RedirectResponse {

        Validator::make($request->all(),
        [
            'email' => 'required|email',
            'password' => 'required'
        ],
        [
            'email.required' => 'El correo es requerido',
            'email.email' => 'El correo no es válido',

            'password.required' => 'La contraseña es requerida'
        ])->validate();

        try {

            $credentials = $request->only('email', 'password');
            $remember = $request->boolean('remember');

            if (!Auth::attempt($credentials, $remember)) {

                // Generic message on purpose: it must not reveal whether the email exists
                Session::flash('message', [ 'content' => "Correo o contraseña incorrectos.", 'type' => 'error' ]);
                return redirect()->back()->withInput($request->only('email'));
            }

            // New session id after login: prevents session fixation
            $request->session()->regenerate();

            return redirect()->intended(route('home.index'));

        } catch(Exception $ex) {

            Log::error($ex);
            Session::flash('message', [ 'content' => "Ha ocurrido un error.", 'type' => 'error' ]);
            return redirect()->back();
        }
    }

    public function logout(Request $request) : RedirectResponse {

        Auth::guard('web')->logout();

        // Destroy the session data and issue a new CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Session::flash('message', [ 'content' => "Sesión cerrada.", 'type' => 'success' ]);
        return redirect()->route('login');
    }
}
