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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->float('value');
            $table->string('type');
            $table->string('date_evaluation');
             $table->enum('session', ['normale', 'rattrapage']);
            $table->foreignId('inscription_id')->constrained()->onDelete('cascade');
            $table->foreignId('ue_id')->constrained()->onDelete('cascade');
            $table->foreignId('ecue_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
