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
        Schema::table('logs', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('student_id')->nullable()->after('secretary_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs', function (Blueprint $table) {
             $table->dropColumn(['admin_id', 'student_id']);
        });
    }
};
