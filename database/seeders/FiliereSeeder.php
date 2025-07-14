<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Filiere;
use Illuminate\Support\Facades\DB;


class FiliereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {     // Désactiver les clés étrangères pour éviter les conflits lors de la suppression
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Supprimer toutes les données existantes de la table 'filieres'
        Filiere::truncate();

        // Réactiver les clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        // Insérer les filières avec les timestamps
        Filiere::insert([
            ['name' => 'Informatique de Gestion'],
            ['name' => 'Gestion Financière et Comptable'],
            ['name' => 'Statistiques'],
            ['name' => 'Planification'],
            ['name' => 'Gestion Commerciale'],
            ['name' => 'Gestion Transport et Logistique'],
            ['name' => 'Gestion des Ressources Humaines'],
            ['name' => 'Gestion des Banques et Assurances'],
        ]);
    }
}
