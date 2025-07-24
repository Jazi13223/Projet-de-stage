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
        Schema::table('ues', function (Blueprint $table) {
             // Ajouter admin_id nullable (clé étrangère vers users)
            if (!Schema::hasColumn('ues', 'admin_id')) {
                $table->foreignId('admin_id')
                      ->nullable()
                      ->constrained('users')
                      ->nullOnDelete(); // équivalent à onDelete('set null')
            }

            // Rendre secretary_id nullable
            $table->unsignedBigInteger('secretary_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ues', function (Blueprint $table) {
              // Supprimer admin_id
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');

            // Revenir à NOT NULL sur secretary_id
            $table->unsignedBigInteger('secretary_id')->nullable(false)->change();
        });
    }
};
