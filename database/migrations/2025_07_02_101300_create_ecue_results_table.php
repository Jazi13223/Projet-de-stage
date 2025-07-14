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
        Schema::create('ecue_results', function (Blueprint $table) {
            $table->id();
            $table->float('initial_grade');
            $table->float('resit_grade')->nullable();
            $table->float('final_grade');
            $table->boolean('validated');
            $table->enum('session', ['normale', 'rattrapage']);
            $table->foreignId('ecue_id')->constrained()->onDelete('cascade');
            $table->foreignId('ue_result_id')->constrained()->onDelete('cascade');
            $table->foreignId('inscription_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecue_results');
    }
};
