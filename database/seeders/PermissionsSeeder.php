<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['code' => 'teams.show',   'description' => 'Ver Equipos',      'module' => 'Equipos'],
            ['code' => 'teams.create', 'description' => 'Crear Equipos',    'module' => 'Equipos'],
            ['code' => 'teams.update', 'description' => 'Editar Equipos',   'module' => 'Equipos'],
            ['code' => 'teams.delete', 'description' => 'Eliminar Equipos', 'module' => 'Equipos'],
            ['code' => 'users.show',   'description' => 'Ver Usuarios',     'module' => 'Usuarios'],
            ['code' => 'users.create', 'description' => 'Crear Usuarios',   'module' => 'Usuarios'],
            ['code' => 'users.update', 'description' => 'Editar Usuarios',  'module' => 'Usuarios'],
        ];

        foreach ($permissions as $data) {

            // Idempotent: if the code already exists, skip it
            $permission = Permission::where('code', '=', $data['code'])->first();

            if (!empty($permission)) {
                continue;
            }

            $permission = new Permission();
            $permission->code = $data['code'];
            $permission->description = $data['description'];
            $permission->module = $data['module'];
            $permission->save();
        }
    }
}
