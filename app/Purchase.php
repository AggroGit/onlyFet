<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
      'user_id', 'pay_in_hand'
    ];


    // all the orders of the purchasde
    public function orders()
    {
       return $this->hasMany('App\Order');
    }

    // user of the purchase
    public function user()
    {
      return $this->belongsTo('App\User');
    }

    // calcula el precio
    public function calcPrice()
    {
      $this->total_price = $this->orders()-sum('price');
      $this->save();
    }

    public function cancel()
    {
      $this->status = "canceled";
      $this->orders()->update([
        "status" => "canceled"
      ])
      $this-calcPrice();
    }


    public function checkIfComplete()
    {
      if($this->orders()->count() == 0) {
        $this->status = "delivered";
        $this->save();
        return true;
      }
      return false;
    }


}
