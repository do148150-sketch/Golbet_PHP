<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
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

    // ---------------------------------------------------------------
    // Registro
    // ---------------------------------------------------------------

    public function register() {

        return view('auth/register');
    }

    public function registerPost(Request $request) : RedirectResponse {

        Validator::make($request->all(),
        [
            'name'     => 'required|max:120',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ],
        [
            'name.required'      => 'El nombre es requerido',
            'name.max'           => 'El nombre no puede tener más de :max caracteres',
            'email.required'     => 'El correo es requerido',
            'email.email'        => 'El correo no es válido',
            'email.max'          => 'El correo no puede tener más de :max caracteres',
            'email.unique'       => 'Ya existe un usuario con ese correo',
            'password.required'  => 'La contraseña es requerida',
            'password.min'       => 'La contraseña debe tener al menos :min caracteres',
            'password.confirmed' => 'La confirmación no coincide con la contraseña',
        ])->validate();

        try {

            // El rol es fijo: el formulario nunca lo escoge. Si lo escogiera,
            // cualquiera podria registrarse como Administrador.
            $role = Role::where('name', '=', 'Apostador')->first();

            if (empty($role)) {

                Session::flash('message', [ 'content' => "El registro no está disponible en este momento.", 'type' => 'error' ]);
                return redirect()->back()->withInput($request->only('name', 'email'));
            }

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role_id = $role->id;
            // El saldo inicial lo pone la base de datos: 100.000 FutCoins
            $user->save();

            // Entra directo con su cuenta nueva
            Auth::login($user);
            $request->session()->regenerate();

            Session::flash('message', [ 'content' => "¡Bienvenido a GolBet! Tu cuenta fue creada.", 'type' => 'success' ]);
            return redirect()->route('profile.show');

        } catch(Exception $ex) {

            Log::error($ex);
            Session::flash('message', [ 'content' => "Ha ocurrido un error.", 'type' => 'error' ]);
            return redirect()->back()->withInput($request->only('name', 'email'));
        }
    }

    // ---------------------------------------------------------------
    // Recuperar contraseña
    // ---------------------------------------------------------------

    public function forgotPassword() {

        return view('auth/forgot-password');
    }

    /**
     * Genera el token y envia el enlace de recuperacion.
     *
     * Con MAIL_MAILER=log el correo no sale: queda escrito en
     * storage/logs/laravel.log, con el enlace listo para abrir.
     */
    public function forgotPasswordPost(Request $request) : RedirectResponse {

        Validator::make($request->all(),
        [
            'email' => 'required|email',
        ],
        [
            'email.required' => 'El correo es requerido',
            'email.email'    => 'El correo no es válido',
        ])->validate();

        try {

            $status = Password::sendResetLink($request->only('email'));

            if ($status === Password::RESET_THROTTLED) {

                Session::flash('message', [ 'content' => "Espera un momento antes de pedir otro enlace.", 'type' => 'error' ]);
                return redirect()->back()->withInput($request->only('email'));
            }

            // Mismo mensaje exista o no el correo: no se revela que cuentas hay
            Session::flash('message', [
                'content' => "Si el correo está registrado, te enviamos un enlace para restablecer la contraseña.",
                'type'    => 'success',
            ]);
            return redirect()->route('login');

        } catch(Exception $ex) {

            Log::error($ex);
            Session::flash('message', [ 'content' => "Ha ocurrido un error.", 'type' => 'error' ]);
            return redirect()->back();
        }
    }

    public function resetPassword(Request $request, $token) {

        return view('auth/reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPasswordPost(Request $request) : RedirectResponse {

        Validator::make($request->all(),
        [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ],
        [
            'token.required'     => 'El enlace no es válido',
            'email.required'     => 'El correo es requerido',
            'email.email'        => 'El correo no es válido',
            'password.required'  => 'La contraseña es requerida',
            'password.min'       => 'La contraseña debe tener al menos :min caracteres',
            'password.confirmed' => 'La confirmación no coincide con la contraseña',
        ])->validate();

        try {

            // Password::reset comprueba que el token exista, sea de ese correo
            // y no haya vencido (60 minutos). Despues lo borra: sirve una sola vez.
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {

                    $user->password = Hash::make($password);
                    $user->setRememberToken(Str::random(60));
                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            if ($status !== Password::PASSWORD_RESET) {

                Session::flash('message', [ 'content' => "El enlace no es válido o ya venció. Pide uno nuevo.", 'type' => 'error' ]);
                return redirect()->back()->withInput($request->only('email'));
            }

            Session::flash('message', [ 'content' => "Contraseña restablecida. Ya puedes iniciar sesión.", 'type' => 'success' ]);
            return redirect()->route('login');

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
