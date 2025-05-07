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
        Schema::create('module_contents', function (Blueprint $table) {
            $table->id('content_id');
            $table->foreignId('module_id')->constrained('modules', 'module_id');
            $table->enum('content_type', ['text', 'video', 'quiz', 'assignment', 'discussion']);
            $table->string('content_title', 100);
            $table->text('content_data');
            $table->foreignId('learning_style_id')->nullable()->constrained('learning_styles', 'style_id');
            $table->integer('sequence_order');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_contents');
    }
};
