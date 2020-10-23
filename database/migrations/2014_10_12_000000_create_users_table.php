<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surnames')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->unique();
            $table->string('nickname')->nullable()->unique();
            $table->string('lang')->default('es');
            $table->boolean('want_emails')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('stripe_reciver_id')->nullable();
            $table->rememberToken();
            $table->boolean('admin')->default(false);
            $table->string('country')
                  ->nullable()
                  ->default('ES');
            $table->string('type')
                  ->nullable()
                  ->default('client');
            $table->string('temporal_token')
                  ->nullable();
            $table->timestamps();
            // profile image
            $table->integer('image_id')
                  ->references('id')
                  ->on('images')
                  ->onDelete('cascade')
                  ->onUpdate('cascade')
                  ->nullable();
            // Redes sociales
            $table->text('social_token')->nullable();
            $table->string('social_name')->nullable();

        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
