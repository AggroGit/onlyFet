<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // productos
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')
                  ->nullable();
            $table->text('description')
                  ->nullable();
            $table->integer('order')->nullable();
            $table->boolean('hidden')->default(false);
            $table->double('price', 8, 2)->nullable();
            $table->double('offer_price', 8, 2)->nullable();
            $table->integer('sales')->nullable()->default(1);
            $table->integer('views')->nullable()->default(1);
            // price per unit, pack_of_units, ml, g, kg, L
            $table->string('price_per')
                  ->default("unit");
            $table->integer('category_id')
                  ->nullable()
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        // productos
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')
                  ->nullable();
            $table->text('description')
                  ->nullable();
            $table->string('icon')
                  ->nullable();
        });

        // imagenes de productos
        Schema::create('products_images', function (Blueprint $table) {
          $table->integer('image_id')
                ->references('id')
                ->on('images')
                ->onDelete('cascade')
                ->onUpdate('cascade');

          $table->integer('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          $table->timestamps();
        });

        // ordenes
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // cantidad entera
            $table->integer('quantity')->default(1);
            // si es de peso o liquido
            $table->double('howmuch')->nulable();
            // en caso de devolución se guardará el stripe id de la devolución
            $table->string('refund_stripe_id')->nullable();
            // precio de la orden
            $table->double('price', 8, 2);
            // si contiene oferta
            $table->boolean('is_offer')->default(false);
            // selected, pending, canceled, failed, delivered, finished
            $table->string('status')->default('selected');
            // guarda el price_per del producto, por si el priducto cambia
            $table->string('price_per');
            // descripcion
            $table->text('description')->nullable();
            // usuario quien hace la orden
            $table->integer('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            // product
            $table->integer('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            // the purchase
            $table->integer('purchase_id')
                  ->nullable()
                  ->references('id')
                  ->on('purchases')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            // the business
            $table->integer('business_id')
                  ->nullable()
                  ->references('id')
                  ->on('business')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->timestamps();
            // si tiene descuento
            $table->integer('discount_id')
                  ->nullable()
                  ->references('id')
                  ->on('discounts')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        // Éste representa una compra de diferentes órdenes
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            // el precio que se cobra al cliente
            $table->double('total_price', 8, 2);
            // el id del pago al cliente
            $table->string('stripe_payment_id')->nullable();
             // en caso que se devuelva
            $table->string('stripe_refound_id')->nullable();
            // status, pending, failed, canceled, delivered, finished
            $table->string('status')->default('pending');
            // intentos de pagos
            $table->integer('payment_tries')->default(0);
            //
            $table->boolean('paid')->default(false);
            // cuando se hará el proximo intento de pago
            $table->timestamp('nextTry')->nullable();
            // comisiones de Stripe
            $table->double('stripe_commisions', 8, 2)->nullable();
            // nuestra comision
            $table->double('our_comision', 8, 2)->nullable();
            // solo será true cuando esté finished
            $table->boolean('completed')->default(false);
            // si es pago en mano
            $table->boolean('pay_in_hand');
            // usuario que compra
            $table->integer('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->timestamps();

        });

        // desucuento de una orden
        // PENDING
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();

            $table->integer('percentage_dicount');
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
        Schema::dropIfExists('products');
        Schema::dropIfExists('products_images');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('discounts');
    }
}
