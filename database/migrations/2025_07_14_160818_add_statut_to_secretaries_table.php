<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('secretaries', function (Blueprint $table) {
             $table->enum('statut', ['Actif', 'Inactif'])->default('Actif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('secretaries', function (Blueprint $table) {
              $table->dropColumn('statut');
        });
    }
};
