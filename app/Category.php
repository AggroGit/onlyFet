<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    public  static function tabletate($data = null) {
      return [
        'headers' => [
          'Nombre' =>  'name',

        ],
        'data'  =>  $data,
        'options' => [
          'edit'    => true,
          'add'     => true,
          'remove'  => true,
        ],
        'singular' => 'category',
        'name'  => 'Categorias',

      ];

    }
}
