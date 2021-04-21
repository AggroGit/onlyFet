<?php

namespace App\Services\Shopping;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Publication as Post;
use App\Mail\BasicMail;
use App\Jobs\sendMail;
use Carbon\Carbon;
use App\Business;
use App\Purchase;
use App\Product;
use App\User;
use App\Order;
use Exception;




class ShoppingDomain
{
  protected $user,$business;

  public function __construct(Int $user_id=null)
  {
    $this->business = Business::find(1);
    if($user = User::find($user_id)) {
      $this->user = $user;
    } else {
      $this->user = auth()->user()?? null;
    }

  }

  public function createOrder($product,$quantity = 1,$howMuch = 1)
  {
    $order =  new Order([
      'product_id' => $product->id,
      'quantity'  => $quantity,
      'howmuch'  => $howMuch,
      'price_per' => $product->price_per,
      'user_id' => $this->user->id,
      'business_id' => $this->business->id
    ]);
    $order->price = $this->calcPriceOfOrder($order);
    $order->save();
    //
    return $order;
  }


  public function calcPriceOfOrder(Order $order)
  {
    return round(
      $order->product->price*$order->howmuch*$order->quantity,2
    );
  }


  public function buy()
  {
    if($this->user->direction == null or $this->user->direction == "" or $this->user->cp == "" or $this->user->direction == null)
    throw new Exception('Falta dirección de envio',211);


    $comision = 0;
    $price = $this->RealshoppingCartPrice();
    // si el precio es mayor
    if($price < env('MIN_PRICE',5)) {
      throw new Exception('No llegas al precio mínimo',205);
    }

    // si el cliente lo paga
    if(!$payment = $this->user->cobrar($price,$comision)) {
      throw new Exception('No money in user account',201);
    }

    // cremos el purchase
    $purchase = $this->createPurchsase($price,$payment->id,$comision);

    // marcamos las ordenes como ya pagadas
    $this->user->shoppingCart()->update([
      "purchase_id" => $purchase->id,
      "status" => "pending"
    ]);

    // devolvemos el purchase
    return $purchase;



  }

  public function RealshoppingCartPrice()
  {
    return round(
        $this->user->shoppingCart->sum('price') + $this->business->sending_price,2
      );
  }

  public function priceOfOrders()
  {
    return $this->user->shoppingCart->sum('price');
  }

  public function createPurchsase($totalPrice,$stripe_payment,$comisions)
  {
    //
    $purchase = new Purchase();
    $purchase->user_id = $this->user->id;
    $purchase->stripe_payment_id = $stripe_payment;
    $purchase->total_price = $totalPrice;
    $purchase->stripe_commisions = $comisions;
    $purchase->sending_price = $this->getSendingPrice();
    $purchase->save();
    //
    return $purchase;
  }

  public function notifyNewPurchase(Purchase $purchase)
  {
    $data = [
      "title"         => "Nuevo pedido de la tienda de ",
      "logoInTitle"   => true,
      "text"          => "Hay un nuevo pedido que gestionar, accede al backOffice para ver el pedido",
      "option"        => [
        'text'  =>  "Gestionar Pedido",
        'url'   =>  url("/admin/purchase/$purchase->id")
      ]
    ];
    sendMail::dispatch(new BasicMail($data),"shop@onlyfet.com");
    //
    $users = User::where("admin",true)->get();
    foreach ($users as $user) {
      sendMail::dispatch(new BasicMail($data),$user->email);
    }
  }

  public function notifyCostumer(Purchase $purchase)
  {
    $data = [
      "title"         => "Tienes un pedido en OnlyFet",
      "logoInTitle"   => true,
      "text"          => "Para ver el estado de tu pedido solo tienes que acceder a través de la web",
      "option"        => [
        'text'  =>  "Ver Pedido",
        'url'   =>  url("/shop/history")
      ]
    ];
    sendMail::dispatch(new BasicMail($data),$purchase->user->email);
  }

  public function removeOrders($id)
  {
    return $this->user->shoppingCart()->where('id',$id)->delete();
  }

  public function cart()
  {
    return $this->user->shoppingCart();
  }

  public function getSendingPrice()
  {
    return $this->business->sending_price;
  }

  public function getPurchases()
  {
    return $this->user->purchases;
  }

  public function returnProduct($product_id)
  {
    if(!$product = Product::find($product_id))
      throw new Exception('No existe éste producto',900);
    return $product;
  }








}
