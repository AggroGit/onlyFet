<?php

namespace App\Services\Shopping;

use App\Services\Shopping\ShoppingDomain;

// use App\Services\Products\ProductServiceProvider;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Product;
use App\Image;
use App\User;




class ShoppingServiceProvider extends ShoppingDomain
{

  // añade una order a carrito
  public function addOrderToCart($product,$quantity=1)
  {
    // create Order
    return $this->createOrder($product,$quantity);
  }

  public function buyShippingCart()
  {
    // compramos
    $purchase = $this->buy();
    // enviamos emails de compra a onoyfet
    $this->notifyNewPurchase($purchase);
    // enviamos email al cliente
    $this->notifyCostumer($purchase);
  }

  public function quitOrdersToCart($id)
  {
    return $this->removeOrders($id);
  }

  public function getShoppingCart()
  {
    return [
      "orders" => $this->cart()->with('product')->get(),
      "price_orders" => $this->priceOfOrders(),
      "sending_price" => $this->getSendingPrice(),
      "total_price" => $this->RealshoppingCartPrice(),
    ];
  }

  public function getHistory()
  {
    return $this->getPurchases();
  }

  public function getProduct($product_id)
  {
    return $this->returnProduct($product_id);
  }








}
