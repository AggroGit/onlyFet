<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // the chat
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->timestamps(0);
            $table->integer('max')
                  ->nullable()
                  ->default(null);
            $table->string('name')
                  ->nullable();
            $table->boolean('open')
                  ->default(true);
        });

        // user belongs to chat
        Schema::create('chats_users', function (Blueprint $table) {
          $table->integer('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

          $table->integer('chat_id')
                ->references('id')
                ->on('chats')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          // remove chat
          $table->boolean('hidden')
                ->nullable()
                ->default(false);
          // block chat
          $table->boolean('blocked')
                ->nullable()
                ->default(false);

        });


        // images of a message
        Schema::create('messages_images', function (Blueprint $table) {

          $table->integer('message_id')
                ->references('id')
                ->on('messages')
                ->onDelete('cascade')
                ->onUpdate('cascade');

          $table->integer('image_id')
                ->references('id')
                ->on('image')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });

        // videos of a message
        Schema::create('messages_videos', function (Blueprint $table) {
          $table->integer('message_id')
                ->references('id')
                ->on('messages')
                ->onDelete('cascade')
                ->onUpdate('cascade');

          $table->integer('video_id')
                ->references('id')
                ->on('videos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // the message of a chat
        Schema::create('messages', function (Blueprint $table) {
          $table->id();
          $table->boolean('forPay')
                ->default(false);
          $table->integer('price')
                ->nullable();
          $table->integer('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          $table->text('message');
          $table->timestamps(0);
          $table->integer('chat_id')
                ->references('id')
                ->on('chats')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          $table->string('token')
                ->nullable();
          $table->boolean('read')
                ->default(false);
          // auction
          $table->integer('auction_id')
                ->nullable()
                ->references('id')
                ->on('auctions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('reports', function (Blueprint $table) {
          $table->id();
          $table->timestamps(0);
          $table->text('reportText');
          $table->integer('user_id')
                ->nullable()
                ->references('id')
                ->on('users');
          $table->integer('to_user_id')
                ->nullable()
                ->references('id')
                ->on('users');
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
        Schema::dropIfExists('chats');
        Schema::dropIfExists('chats_users');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('messages_images');
        Schema::dropIfExists('messages_videos');
        Schema::dropIfExists('reports');

    }
}
