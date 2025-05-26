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
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id('room_id');
            $table->integer('learning_style_id');
            $table->string('room_name');
            $table->timestamps();
            $table->foreign('learning_style_id')->references('style_id')->on('learning_styles');
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id('message_id');
            $table->integer('room_id');
            $table->integer('user_id');
            $table->text('message');
            $table->timestamps();
            $table->foreign('room_id')->references('room_id')->on('chat_rooms');
            $table->foreign('user_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_rooms');
    }
};
