<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            ['name' => 'Atlético Nacional',        'city' => 'Medellín',     'crest_url' => 'https://placehold.co/80x80/006633/ffffff?text=NAC'],
            ['name' => 'Independiente Medellín',   'city' => 'Medellín',     'crest_url' => 'https://placehold.co/80x80/cc0000/ffffff?text=DIM'],
            ['name' => 'Millonarios',              'city' => 'Bogotá',       'crest_url' => 'https://placehold.co/80x80/003399/ffffff?text=MIL'],
            ['name' => 'Independiente Santa Fe',   'city' => 'Bogotá',       'crest_url' => 'https://placehold.co/80x80/cc0000/ffffff?text=SFE'],
            ['name' => 'América de Cali',          'city' => 'Cali',         'crest_url' => 'https://placehold.co/80x80/e60000/ffffff?text=AME'],
            ['name' => 'Deportivo Cali',           'city' => 'Cali',         'crest_url' => 'https://placehold.co/80x80/00794d/ffffff?text=CAL'],
            ['name' => 'Junior de Barranquilla',   'city' => 'Barranquilla', 'crest_url' => 'https://placehold.co/80x80/d40026/ffffff?text=JUN'],
            ['name' => 'Once Caldas',              'city' => 'Manizales',    'crest_url' => 'https://placehold.co/80x80/ffffff/000000?text=ONC'],
        ];

        foreach ($teams as $data) {
            $team = new Team();
            $team->name = $data['name'];
            $team->city = $data['city'];
            $team->crest_url = $data['crest_url'];
            $team->save();
        }
    }
}
