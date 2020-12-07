<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('name')
                  ->nullable();
            $table->string('alt')
                  ->nullable();
            $table->string("format")
                  ->nullable();
            $table->string("url")
                  ->nullable();
            $table->string("base")
                  ->default("storage")
                  ->nullable();
            $table->string("path")
                  ->nullable();
            //
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
            // if we want to delete the image in the future
            $table->boolean('remove_future')
                  ->default(false);

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
        Schema::dropIfExists('images');
    }
}
