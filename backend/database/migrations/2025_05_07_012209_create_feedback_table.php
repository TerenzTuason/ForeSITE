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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id('feedback_id');
            $table->foreignId('faculty_id')->constrained('users', 'user_id');
            $table->foreignId('student_id')->constrained('users', 'user_id');
            $table->foreignId('module_id')->constrained('modules', 'module_id');
            $table->text('feedback_text');
            $table->integer('rating')->nullable()->check('rating >= 0 AND rating <= 5');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
