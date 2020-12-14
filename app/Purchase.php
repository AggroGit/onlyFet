<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Business;

class Purchase extends Model
{


    protected $fillable = [
      'user_id', 'pay_in_hand'
    ];

    protected $appends = [
        'fecha'
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
      ]);
      $this-calcPrice();
    }

    public function getFechaAttribute()
    {
      return Carbon::parse($this->created_at)->format('d/m/Y')?? "Uknown";
    }

    public function getOurPriceAttribute()
    {
      return $this->total_price - $this->stripe_commisions;
    }

    public  static function tabletate($data = null) {
      return [
        'headers' => [
          'Creado el'  => 'created_at',
          'Estado'  => 'EstadoPedido',
          'Cobrado a cliente'  => 'total_price',
          'Comisión Stripe' => 'stripe_commisions',
          'Total Resultante'   => 'ourPrice',
          'Dirección' => 'DirectionClient',
          'Usuario' => [
            'model_name' => 'user',
            'select'     => User::all(), // data al seleccionar en crear
            'show'       => 'name',
            'multiple'   => false,
            'url'        => "admin/user/edit"
          ],

        ],
        'data'  =>  $data,
        'options' => [
          'edit'    => true,
        ],
        'singular' => 'purchase',
        'name'  => 'Pedidos',

      ];

    }





}
