<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('content')->nullable();
            $table->string('type')->default('default');
            // $table->integer('likes')->default(0);
            // $table->integer('comments')->default(0);
            // usuario quien hace la publicación
            $table->integer('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        // imagenes de publicaciones
        Schema::create('publications_images', function (Blueprint $table) {
          $table->integer('image_id')
                ->references('id')
                ->on('images')
                ->onDelete('cascade')
                ->onUpdate('cascade');

          $table->integer('publication_id')
                ->references('id')
                ->on('publications')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          $table->timestamps();
          });

          // Likes
          Schema::create('likes', function (Blueprint $table) {
            $table->id();
            // user que da <3
            $table->integer('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            // publicación
            $table->integer('publication_id')
                  ->references('id')
                  ->on('publications')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->timestamps();

            });

            Schema::create('comments', function (Blueprint $table) {
              $table->id();
              // user
              $table->integer('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
              // publicación
              $table->integer('publication_id')
                    ->references('id')
                    ->on('publications')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
              // comentario
              $table->text('comment');
              //
              $table->timestamps();

              });

              Schema::create('hastags', function (Blueprint $table) {
                  $table->id();
                  $table->text('text')->nullable();
              });

              Schema::create('publications_hastags', function (Blueprint $table) {
                  $table->id();
                  $table->integer('hastag_id')
                        ->references('id')
                        ->on('hastags')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
                  //
                  $table->integer('publication_id')
                        ->references('id')
                        ->on('publications')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
              });







    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publications');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('likes');
        Schema::dropIfExists('publications_images');
        Schema::dropIfExists('publications_hastags');
        Schema::dropIfExists('hastags');

    }
}
