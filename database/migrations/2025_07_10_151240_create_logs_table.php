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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
              $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Utilisateur (secrétaire)
            $table->string('action'); // Ex: "Ajout d’un étudiant", "Modification d’une note"
            $table->text('description')->nullable(); // Détails supplémentaires (nom de l'étudiant, anciennes valeurs...)
            $table->timestamps(); // created_at = moment de l'action
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
