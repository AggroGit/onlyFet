<?php





namespace App\Traits;

use App\Purchase;
use App\Product;
use App\Order;

/**
 * Shopping for user
 *
 */
trait shopping
{
  // all details of a cart
  public function completeShoppingCart()
  {
    return [
      "orders" => $this->shoppingCart()->with('products'),
      "total"  => $this->shoppingCart->sum('price'),
    ];
  }

  // añade un producto a la cesta
  public function addProduct($product_id, $description, $quantity, $howmuch)
  {
    if(!$product = Product::find($product_id)) {
      return false;
    }
    // creamos la orden
    $order = new Order([
      'product_id'  => $product_id,
      'description' => $description,
      'quantity'    => $quantity,
      'howmuch'     => $howmuch,
      'user_id'     => $this->id,
      'price_per'   => $product->price_per
    ]);
    // si es oferta
    if($product->offer_price !== null) {
      $order->is_offer = true;
    }
    // save
    $order->save();
    // calculamos el precio
    $order->calcPrice();
    return $order;

  }

  // realiza una compra
  public function buy($pay_in_hand = false)
  {
    // si es mas grande de uno
    if($this->shoppingCart->count() > 0) {
      // creamos un purchase
      $purchase = new Purchase([
        'user_id'     => $this->id,
        'pay_in_hand' => $pay_in_hand,
      ]);
      $purchase->save();
      $purchase->calcPrice();
      // actualizamos las ordenes
      $this->shoppingCart()->update([
        "status"      => 'pending',
        "purchase_id" => $purchase->id

      ]);
      // ahora cobramos al usuario
      // if(!$pay_in_hand) {
      //   $comision = 0;
      //   if($stripe_id = $this->cobrar($purchase->prise,$comision) and $stripe_id!== false){
      //     $purchase->stripe_payment_id =  $stripe_id;
      //     $purchase->stripe_commisions =  $comision;
      //     $purchase->paid = true;
      //     $purchase->save();
      //   } else {
      //     return true;
      //   }
      //
      // }
      return true;
    }
    return false;
  }







}
