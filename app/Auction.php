<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;


class Auction extends Model
{
    protected $with = ["images",'user','winner','current'];
    //
    protected $appends =['remainingSeconds'];
    //
    protected $fillable = [
      'initial_price', 'final_price', 'title', 'description'
    ];
    // owner user
    public function user()
    {
      return $this->belongsTo('App\User')->without('plans');
    }

    // current auction
    public function current()
    {
      return $this->belongsTo('App\User','current_id');
    }

    // winner of the auction
    public function winner()
    {
      return $this->belongsTo('App\User','winner_id');
    }

    // images
    public function images()
    {
      return $this->belongsToMany('App\Image', 'auctions_images');
    }

    public function getremainingSecondsAttribute()
    {
      return Carbon::parse(now())->diffInSeconds($this->finish_at?? now(), false);
    }

    public  static function tabletate($data = null) {
      return [
        'headers' => [
          'Titulo' =>  'title',
          'Creador' => [
            'model_name' => 'user',
            'select'     => User::all(), // data al seleccionar en crear
            'show'       => 'name',
            'multiple'   => false,
            'url'        => "admin/user/edit"
          ],
          'Actual pujador' => [
            'model_name' => 'current',
            'select'     => User::all(), // data al seleccionar en crear
            'show'       => 'name',
            'multiple'   => false,
            'url'        => "admin/user/edit"
          ],
          'Ganador' => [
            'model_name' => 'winner',
            'select'     => User::all(), // data al seleccionar en crear
            'show'       => 'name',
            'multiple'   => false,
            'url'        => "admin/user/edit"
          ],
          'Puja actual' => 'current_auction',
          'Precio inicial' => 'initial_price',
          'Precio final' => 'final_price',
          'Finaliza el' => 'finish_at',
        ],
        'data'  =>  $data,
        'options' => [
          'edit'    => true,
          'remove'  => true,
          'image'   => true
        ],
        'singular' => 'auction',
        'name'  => 'Subastas',

      ];

    }
}
