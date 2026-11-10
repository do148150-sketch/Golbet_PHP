<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Roles
        $adminRole = $this->createRole('Administrador');
        $bettorRole = $this->createRole('Apostador');

        // 2. Permissions per role
        // Administrador: every permission
        foreach (Permission::all() as $permission) {
            $this->assignPermission($adminRole, $permission);
        }

        // Apostador: only teams.show
        $teamsShow = Permission::where('code', '=', 'teams.show')->first();
        if (!empty($teamsShow)) {
            $this->assignPermission($bettorRole, $teamsShow);
        }

        // 3. Users
        $this->createUser('Administrador', 'admin@golbet.com', '1234', $adminRole);
        $this->createUser('Juan Apostador', 'apostador@golbet.com', '1234', $bettorRole);

        // The test user from DatabaseSeeder has no role: give it Administrador
        $testUser = User::where('email', '=', 'test@example.com')->first();
        if (!empty($testUser) && empty($testUser->role_id)) {
            $testUser->role_id = $adminRole->id;
            $testUser->save();
        }
    }

    private function createRole($name)
    {
        $role = Role::where('name', '=', $name)->first();

        if (empty($role)) {
            $role = new Role();
            $role->name = $name;
            $role->save();
        }

        return $role;
    }

    private function assignPermission($role, $permission)
    {
        $exists = RolePermission::where('role_id', '=', $role->id)
                                ->where('permission_id', '=', $permission->id)
                                ->exists();

        if ($exists) {
            return;
        }

        $rolePermission = new RolePermission();
        $rolePermission->role_id = $role->id;
        $rolePermission->permission_id = $permission->id;
        $rolePermission->save();
    }

    private function createUser($name, $email, $password, $role)
    {
        $user = User::where('email', '=', $email)->first();

        if (!empty($user)) {
            return;
        }

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->role_id = $role->id;
        $user->save();
    }
}
