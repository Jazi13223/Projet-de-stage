<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Semester;
use App\Models\Year;
use Illuminate\Support\Facades\DB;


class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {     // Désactivation des contraintes de clé étrangère
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Suppression de toutes les données existantes dans la table 'semesters'
        Semester::truncate();

        // Réactivation des clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Récupérer les années existantes
        $year1 = Year::where('academic_year', 'Année 1')->first();
        $year2 = Year::where('academic_year', 'Année 2')->first();
        $year3 = Year::where('academic_year', 'Année 3')->first();

        // Insertion des semestres
        Semester::insert([
            ['name' => 'Semestre 1', 'year_id' => $year1->id],
            ['name' => 'Semestre 2', 'year_id' => $year1->id],
            ['name' => 'Semestre 3', 'year_id' => $year2->id],
            ['name' => 'Semestre 4', 'year_id' => $year2->id],
            ['name' => 'Semestre 5', 'year_id' => $year3->id],
            ['name' => 'Semestre 6', 'year_id' => $year3->id]
        ]);
    }
}
