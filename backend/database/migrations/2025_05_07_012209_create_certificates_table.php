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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id('certificate_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->foreignId('course_id')->constrained('courses', 'course_id');
            $table->timestamp('issue_date')->useCurrent();
            $table->string('certificate_url', 255)->nullable();
            $table->unique(['user_id', 'course_id'], 'unique_certificate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
