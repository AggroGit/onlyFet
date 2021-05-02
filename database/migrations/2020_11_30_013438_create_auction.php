<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('initial_price')->default(1);
            $table->integer('final_price')->nullable();
            $table->integer('current_auction')->default(1);
            $table->string('status')->default('open'); // pending, open, closed, not_paid
            $table->string('title')->nullable();
            $table->text('description');
            $table->datetime('start_at')->nullable();
            $table->datetime('finish_at');
            $table->double('stripe_comision', 8, 2)->nullable();
            $table->string('stripe_payment_id')->nullable();
            $table->integer('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade')
                  ->nullable();
            $table->integer('winner_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade')
                  ->nullable();
            $table->integer('current_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade')
                  ->nullable();

        });

        Schema::create('auctions_images', function (Blueprint $table) {
          $table->integer('image_id')
                ->references('id')
                ->on('images')
                ->onDelete('cascade')
                ->onUpdate('cascade');

          $table->integer('auction_id')
                ->references('id')
                ->on('auctions')
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
        Schema::dropIfExists('auctions');
        Schema::dropIfExists('auctions_images');
    }
}
