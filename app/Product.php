<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $with = ['category'];
    //
    protected $fillable =['name','description','price','category_id'];
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





}
