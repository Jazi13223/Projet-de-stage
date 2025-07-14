<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Year;
use Illuminate\Support\Facades\DB;


class YearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {    // Désactiver les contraintes de clé étrangère
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Supprimer les anciennes données de la table 'years'
        Year::truncate();

        // Réactiver les contraintes de clé étrangère
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');  


         Year::insert([
            ['academic_year' => 'Année 1'],
            ['academic_year' => 'Année 2'],
            ['academic_year' => 'Année 3'],
        ]);
    }
}
