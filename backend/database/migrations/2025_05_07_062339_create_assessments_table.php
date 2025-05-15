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
        Schema::create('assessment_results', function (Blueprint $table) {
            $table->id('result_id');
            $table->string('first_name', 200);
            $table->string('last_name', 200);
            $table->string('department', 200);
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->foreignId('course_id')->constrained('courses', 'course_id');
            $table->json('answers');
            $table->json('result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_results');
    }
};
