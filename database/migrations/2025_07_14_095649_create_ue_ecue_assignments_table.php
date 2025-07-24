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
        Schema::create('ue_ecue_assignments', function (Blueprint $table) {
            $table->id();
             $table->foreignId('ue_assignment_id')->constrained()->onDelete('cascade');
            $table->foreignId('ecue_id')->constrained()->onDelete('cascade');
            $table->float('coefficient');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ue_ecue_assignments');
    }
};
