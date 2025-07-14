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
        Schema::create('ue_results', function (Blueprint $table) {
            $table->id();
            $table->float('initial_average');
            $table->float('resit_average')->nullable();
            $table->float('final_average');
            $table->boolean('validated');
            $table->enum('session', ['normale', 'rattrapage']);
            $table->boolean('recomposed')->nullable();
            $table->boolean('reset')->nullable();
            $table->foreignId('ue_id')->constrained()->onDelete('cascade');
            $table->foreignId('inscription_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ue_results');
    }
};
