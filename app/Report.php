<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Report extends Model
{
    //
    protected $fillable = ['reportText','user_id','to_user_id'];

    public function user()
    {
      return $this->belongsTo('App\User')->without(['image','plans','notifications']);
    }

    public function to_user()
    {
      return $this->belongsTo('App\User','to_user_id')->without(['image','plans','notifications']);
    }

    //
    public  static function tabletate($data = null) {
      return [
        'headers' => [
          'Motivo' =>  'reportText',

          'Usuario' => [
            'model_name' => 'user',
            'select'     => User::all(), // data al seleccionar en crear
            'show'       => 'name',
            'multiple'   => false,
            'url'        => "admin/user/edit"
          ],
          'Reporta a ' => [
            'model_name' => 'to_user',
            'select'     => User::all(), // data al seleccionar en crear
            'show'       => 'name',
            'multiple'   => false,
            'url'        => "admin/user/edit"
          ],
        ],
        'data'  =>  $data,
        'options' => [
          'edit'    => true,
          'add'     => true,
          'remove'  => true,

        ],
        'singular' => 'report',
        'name'  => 'Reportes',

      ];

    }
}
