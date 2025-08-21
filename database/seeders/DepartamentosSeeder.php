<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departamentos')->insert([
            ['nombre' => 'Concepción'],
            ['nombre' => 'San Pedro'],
            ['nombre' => 'Cordillera'],
            ['nombre' => 'Guairá'],
            ['nombre' => 'Caaguazú'],
            ['nombre' => 'Caazapá'],
            ['nombre' => 'Itapúa'],
            ['nombre' => 'Misiones'],
            ['nombre' => 'Paraguarí'],
            ['nombre' => 'Alto Paraná'],
            ['nombre' => 'Central'],
            ['nombre' => 'Ñeembucú'],
            ['nombre' => 'Amambay'],
            ['nombre' => 'Canindeyú'],
            ['nombre' => 'Presidente Hayes'],
            ['nombre' => 'Boquerón'],
            ['nombre' => 'Alto Paraguay'],
        ]);
    }
}
