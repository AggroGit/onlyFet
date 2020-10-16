<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // ordenes
    public function orders()
    {
       return $this->hasMany('App\Order')
                   ->orderBy('created_at','desc');
    }

    // imagenes
    public function images()
    {
      return $this->belongsToMany('App\Image','products_images');
    }

    // category of the product
    public function category()
    {
      return $this->belongsTo('App\Category');
    }

    // usuario quien lo crea
    public function user()
    {
      return $this->belongsTo('App\User');
    }

    // // // sizes of the product
    // public function sizes()
    // {
    //    return $this->hasMany('App\Sizes');
    // }
    //
    //
    // // business of the product
    // public function business()
    // {
    //   return $this->belongsTo('App\Business')->distance(auth()->user()->latitude, auth()->user()->longitude);
    // }

}
