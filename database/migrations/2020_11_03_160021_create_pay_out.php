<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayOut extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      // Éste representa un pago externo
      Schema::create('pay_outs', function (Blueprint $table) {
          $table->id();
          $table->string('stripe_payout_id')->nullable(); // el id del pago al comerciante
          $table->timestamp('money_send_at')->nullable();// cuando se enviará el dinero
          $table->double('price_sended', 8, 2);           // precio real que se ha enviado al comerciante
          $table->double('stripe_comision', 8, 2)->nullable();        //
          $table->double('only_comision', 8, 2)->nullable();          //
          $table->double('influencer_comision', 8, 2)->nullable();    //
          $table->boolean('sended')->default(false);
          $table->boolean('failed')->default(false);
          // usuario que lo recibe
          $table->integer('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          // usuario que lo envía
          $table->integer('from_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          //
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
        Schema::dropIfExists('pay_outs');
    }
}
