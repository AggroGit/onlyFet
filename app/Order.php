<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $with=["product"];
    //
    protected $fillable = [
      'product_id', 'description', 'quantity', 'howmuch', 'user_id', 'price_per'
    ];

    // status que pueden ser finalizados
    public static function statusFinished()
    {
      return [
        'canceled','failed','completed','finished'
      ];
    }

    // status que puede ser entregado
    public static function statusToBeDelivered()
    {
      return [
        'pending'
      ];
    }


    // purchase
    public function purchase()
    {
      return $this->belongsTo('App\Purchase')->without('orders')->with('user');
    }

    // negocio
    public function business()
    {
      return $this->belongsTo('App\Business');
    }


    // the product o f the order
    public function product()
    {
      return $this->belongsTo('App\Product')->with('images');
    }

    // the discount of the order
    public function discount()
    {
      return $this->belongsTo('App\Discount');
    }

    // calcula el precio de la orden
    public function calcPrice()
    {
      $this->quantity = $this->quantity?? 1;
      $this->price = round($this->getTotalPriceByOne() * $this->quantity,2);
      $this->save();
    }

    // nos calcula el precio por una unidad
    public function getTotalPriceByOne()
    {
      $this->howmuch = $this->howmuch?? 1;
      return $this->getPrice()*$this->howmuch;
    }

    // precio, si tiene oferta de oferta, sino no
    public function getPrice()
    {
      // si el precio de oferta es nulo entonces no es oferta
      $this->is_offer = ($this->product->offer_price !== null);
      return $this->product->offer_price?? $this->product->price;
    }


    public function user()
    {
      return $this->belongsTo('App\User');
    }








}
