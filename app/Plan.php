<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Chat;
use App\Plan;
use App\User;
//

class Plan extends Model
{
    protected $appends = ["fecha","userCount",'months'];
    // cuando se actualiza un precio entonces deberíamos cambiar de plan todos los uaurios.
    public function getFechaAttribute()
    {
      return $this->created_at->diffForHumans();
    }

    // usuarios suscritos al plan
    public function usersSuscribed()
    {
      return $this->belongsToMany('App\User', 'users_plans')->withPivot(['stripe_suscription_id']);
    }

    // usuario quien hace el plan
    public function user()
    {
      return $this->belongsTo('App\User');
    }

    // check if is user suscribed
    public function isUser(User $user)
    {
      if ($this->usersSuscribed()->find($user->id)) {
        return true;
      }
      return false;
    }



    public function getInterval($value)
    {
      switch ($value) {
        case 'month1':
          return 1;
          break;
        case 'month3':
        return 3;
        break;
        case 'month6':
        return 6;
        break;
        case 'mont12':
        return 12;
        break;
        default:
          return 12;
          break;
      }
    }

    public function getUserCountAttribute()
    {
      return $this->usersSuscribed->count();
    }

    public function getMonthsAttribute()
    {
      switch ($this->payForEvery) {
        case 'month1':
          return 1;
          break;
        case 'month3':
        return 3;
        break;
        case 'month6':
        return 6;
        break;
        case 'mont12':
        return 12;
        break;
        default:
          return 12;
          break;
      }
    }

    public  static function tabletate($data = null) {
      return [
        'headers' => [
          'Id' => 'id',
          'Paga cada (meses)' => 'months',
          'Precio' => 'price',
          'Precio anterior' => 'oldPrice',
          'Creador' => [
            'model_name' => 'user',
            'select'     => User::all(), // data al seleccionar en crear
            'show'       => 'name',
            'multiple'   => false,
            'url'        => "admin/user/edit"
          ],
          'Usuarios Suscritos' => 'userCount'

        ],
        'data'  =>  $data,
        'options' => [
          'edit'    => true,
          'add'     => true,
          'remove'  => true,
        ],
        'singular' => 'plan',
        'name'  => 'Planes',

      ];

    }
}
