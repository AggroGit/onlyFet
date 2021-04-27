<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    //
    public  static function tabletate($data = null) {
      return [
        'headers' => [
          'Nombre' =>  'title',
          '% para el usuario' => 'percentage_dicount',
          'Caducidad' => 'available_until',
          'Creado el' => 'created_at'
        ],
        'data'  =>  $data,
        'options' => [
          'edit'    => true,
          'add'     => true,
          'remove'  => true,

        ],
        'singular' => 'discount',
        'name'  => 'Descuentos',

      ];

    }

}
