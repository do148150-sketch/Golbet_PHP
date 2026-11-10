<?php

namespace App\Helpers;

use App\Models\RolePermission;
use Illuminate\Support\Facades\Auth;

class RoleHelper {

    /**
     * Does the logged in user's role have this permission?
     * Asked in SQL with an EXISTS: no rows travel, just a yes or no.
     */
    public static function isAuthorized($permissionCode) {

        $user = Auth::user();

        // No session, or a user with no role assigned: nothing is allowed
        if (empty($user) || empty($user->role_id)) {

            return false;
        }

        return RolePermission::join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                             ->where('role_permissions.role_id', '=', $user->role_id)
                             ->where('permissions.code', '=', $permissionCode)
                             ->exists();
    }

    /**
     * Same question, for the views: hides what the user cannot use.
     * The real guard is the middleware; this only tidies the interface.
     */
    public static function can($permissionCode) {

        return self::isAuthorized($permissionCode);
    }
}
