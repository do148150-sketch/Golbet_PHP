<?php

namespace App\Http\Middleware;

use App\Helpers\RoleHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthorizedMiddleware
{
    /**
     * Guards a route with a permission code.
     * Runs on the server for every request: hiding a button is not enough.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $permissionCode): Response
    {
        // 1. Is there a session? If not, to the login screen.
        if (!Auth::check()) {

            return redirect()->route('login');
        }

        // 2. Does the role carry this permission? If not, 403.
        if (!empty($permissionCode)) {

            if (!RoleHelper::isAuthorized($permissionCode)) {

                abort(403, 'No tienes permiso para acceder a esta página.');
            }
        }

        return $next($request);
    }
}
