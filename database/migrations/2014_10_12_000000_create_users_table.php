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
            $table->string('bank_account')->nullable();
            $table->integer('percentage_for_user')->default(80);
            $table->integer('numSuscriptions')->default(0);
            $table->string('phone_number')->nullable();
            $table->string('stripe_product_id')->nullable();
            $table->boolean('wantToBeInfluencer')->default(false);
            $table->boolean('influencer')->default(false); // que ha hecho pasarela
            $table->boolean('updoadRequiredPics')->default(false);
            $table->integer('numUploadedPosts')->default(0);
            $table->boolean('verified')->default(false);
            $table->boolean('stripe_created')->default(false);
            $table->boolean('prices_added')->default(false);
            $table->string('provider')->nullable(); // RRSS
            $table->string('email')->unique()->nullable();
            $table->string('nickname')->nullable()->unique();
            $table->string('lang')->default('en');
            $table->boolean('want_emails')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->date('birthday')->nullable();
            $table->string('stripe_reciver_id')->nullable();
            $table->rememberToken();
            $table->boolean('admin')->default(false);
            $table->string('country')
                  ->nullable()
                  ->default('ES');
            $table->text('description')->nullable();
            $table->string('cp')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('direction')->nullable();
            $table->text('direction_details')->nullable();

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


        // the docuements(images) of the user
        Schema::create('users_documents', function (Blueprint $table) {
          $table->timestamps();
          $table->integer('image_id')
                ->references('id')
                ->on('images')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->nullable();
          $table->string('document_description')->nullable();
          //
          $table->integer('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->nullable();
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
        Schema::dropIfExists('users_documents');
    }
}
