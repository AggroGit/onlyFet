<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Malhal\Geographical\Geographical;
use App\Association;
use Carbon\Carbon;
use App\Schedule;
use App\Image;
use App\Order;
use App\Chat;
use App\User;

class Business extends Model
{
    use Geographical;



    protected $table = "business";
    protected $with = ["images"];
    protected static $kilometers = true;

    protected $fillable = [
        'email', 'name', 'description', 'latitude','longitude', 'phone', 'direction', 'link'
    ];

    protected $casts = [
        'verified' => 'boolean',
    ];


    // images of the business
    public function images()
    {
        return $this->belongsToMany('App\Image','business_images');
    }


    // messages of the chat
    public function chats()
    {
       return $this->hasMany('App\Chat')
                   ->orderBy('created_at','desc');
    }

    // products of the business
    public function products()
    {
      return $this->allProducts()->where('hidden',false);
    }

    // reviews of the business
    public function reviews()
    {
      return $this->hasMany('App\Review');
    }

    public function allProducts()
    {
      return $this->hasMany('App\Product')->orderBy('updated_at','DESC')->orderBy('order','DESC');
    }


    public function user()
    {
      return $this->belongsTo('App\User');
    }

    // category of the business
    public function category()
    {
      return $this->belongsTo('App\Category');
    }

    public function getTotalScoresAttribute()
    {
      return $this->reviews()->count();
    }

    public function discounts()
    {
       return $this->hasMany('App\Discount');
    }



    public function delete()
    {
        $this->products()->delete();
        $this->images()->delete();
        return parent::delete();
    }

    public  static function tabletate($data) {
      return [
        'headers' => [
          "precio de envío" => 'sending_price',
        ],

        'data'  =>  $data,
        'options' => [
          'edit'    => true,
          'remove'  => false,
          'image'   => true,
        ],
        'singular' => 'business',
        'name'  => 'Negocios',

      ];

    }









}
