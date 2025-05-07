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
        Schema::create('roles', function (Blueprint $table) {
            $table->id('role_id');
            $table->enum('role_name', ['student', 'faculty', 'admin']);
            $table->string('description', 255)->nullable();
        });

        // Insert default roles
        DB::table('roles')->insert([
            ['role_name' => 'student', 'description' => 'Regular student user'],
            ['role_name' => 'faculty', 'description' => 'Faculty member with teaching privileges'],
            ['role_name' => 'admin', 'description' => 'System administrator with full access']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
