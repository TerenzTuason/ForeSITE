<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('learning_styles', function (Blueprint $table) {
            $table->id('style_id');
            $table->string('style_name', 50);
            $table->text('description')->nullable();
        });

        // Insert default learning styles based on VARK model
        DB::table('learning_styles')->insert([
            ['style_name' => 'visual', 'description' => 'Preference for visual information like charts, graphs, and diagrams'],
            ['style_name' => 'auditory', 'description' => 'Preference for spoken or heard information'],
            ['style_name' => 'reading/writing', 'description' => 'Preference for information displayed as words'],
            ['style_name' => 'kinesthetic', 'description' => 'Preference for learning through experience and practice']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_styles');
    }
};
