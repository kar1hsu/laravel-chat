<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChatMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('chat_message', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('from_uid')->default(0);
            $table->integer('to_uid')->default(0);
            $table->tinyInteger('message_type')->default(0);
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('chat_message');
    }
}
