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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id('enrollment_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->foreignId('course_id')->constrained('courses', 'course_id');
            $table->timestamp('enrollment_date')->useCurrent();
            $table->enum('completion_status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->timestamp('completion_date')->nullable();
            $table->unique(['user_id', 'course_id'], 'unique_enrollment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
