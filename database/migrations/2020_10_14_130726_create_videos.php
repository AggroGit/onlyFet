<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
          $table->id();
          $table->string('name')
                ->nullable();
          $table->string("format")
                ->nullable();
          $table->string("url")
                ->nullable();
          $table->string("base")
                ->default("storage")
                ->nullable();
          $table->boolean('failed')->default(false);
          $table->boolean('exported')
                ->default(false);
          $table->string("path")
                ->nullable();
          $table->integer('user_id')
                ->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          $table->integer('post_id')
                ->nullable()
                ->references('id')
                ->on('posts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('videos');
    }
}
