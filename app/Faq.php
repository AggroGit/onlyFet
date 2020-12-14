<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    //

    public  static function tabletate($data = null) {
      return [
        'headers' => [
          'Titulo' =>  'name',
          'Texto'  => 'content',
          'Idioma (código país)' => 'lang',

        ],
        'data'  =>  $data,
        'options' => [
          'edit'    => true,
          'add'     => true,
          'remove'  => true,
          'image'   => false,
          'images'  => false,
        ],
        'singular' => 'faq',
        'name'  => 'Faqs',

      ];

    }

}
