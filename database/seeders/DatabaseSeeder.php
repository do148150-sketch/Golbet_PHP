<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Los usuarios reales los crea UserSeeder, con su rol y su contraseña.
        // El User::factory() de la plantilla se quito: no era idempotente
        // (fallaba al correr db:seed dos veces) y dejaba una cuenta sin rol.

        $this->call(TeamSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(UserSeeder::class);
    }
}
