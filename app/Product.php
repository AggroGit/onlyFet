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

    public function delete()
    {
        $this->images()->delete();
        return parent::delete();
    }

    public  static function tabletate($data = null) {
      return [
        'headers' => [
          'Nombre' =>  'name',
          'Precio'  => 'price',
          'Descripción'  => 'description',
          'Orden' => 'order',
          'Categoría' => [
            'model_name' => 'category',
            'select'     => Category::all(), // data al seleccionar en crear
            'show'       => 'name',
            'multiple'   => false,
            'url'        => "admin/category/edit"
          ],
        ],
        'data'  =>  $data,
        'options' => [
          'edit'    => true,
          'add'     => true,
          'remove'  => true,
          'image'   => true,
          'images'  => true,
        ],
        'singular' => 'product',
        'name'  => 'Productos',

      ];

    }





}
