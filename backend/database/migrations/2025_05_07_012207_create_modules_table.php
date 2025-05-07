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
        Schema::create('modules', function (Blueprint $table) {
            $table->id('module_id');
            $table->foreignId('course_id')->constrained('courses', 'course_id');
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->integer('sequence_order');
            $table->foreignId('prerequisite_module_id')->nullable();
            $table->integer('passing_score')->default(75);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            
            // Self-referencing foreign key
            $table->foreign('prerequisite_module_id')->references('module_id')->on('modules')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
