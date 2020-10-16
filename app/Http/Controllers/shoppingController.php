<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class shoppingController extends Controller
{
    //


    public function deliverOrder($order_id, Request $request)
    {
      if(!$order = Order::find($order_id)) {
        return $this->incorrect();
      }
      $order->status = "delivered";
      $order->save();
      $order->purchase->checkIfComplete();
    }

    public function deliverPurcha()
    {
      // code...
    }
}
