<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Laravel\Cashier\Billable;
use App\Traits\Sockeable;
use App\Traits\PayStripe;
use App\Traits\shopping;
use App\Traits\Notify;
use App\Mail\BasicMail;
use App\Jobs\sendMail;



class User extends Authenticatable
{
    use HasApiTokens, Sockeable, Notify, Billable, PayStripe, shopping;

    protected $with=['image','plans', 'notifications','currentAuctions'];

    protected $appends =['canSee','numProducts'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','surnames', 'direction','email', 'password', 'device_token','social_name','social_token','nickname','country','description', 'provider', 'lang'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','temporal_token'
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'influencer' => 'boolean',
    ];


    // the chats of the user
    public function chats()
    {
      return $this->allChats()
                  ->where([
                    ['blocked',false],
                    ['hidden',false]
                  ]);
    }



    // // nos dice si el usuario logeado puede ver su contenido
    public function getCanSeeAttribute()
    {
      // si el usuario es influencer
      if($this->influencer) {
        // si hay usuario logeado
        if($user = auth()->user()) {
          // si el usuario es si mismo no hace falta mostrarlo, pero sí se muestra
          if($this->id == $user->id) {
            return true;
          }
          // si éste logeado está suscrito
          // cogemos los planes
          $myPlans = $this->plans;
          // miramos
          foreach ($myPlans as $plan) {
            if($plan->isUser($user)) {
              return true;
            }
          }
        }
        return false;
      }
      return true;
    }


    //
    public function allChats()
    {
      return $this->belongsToMany('App\Chat','chats_users')->orderBy('updated_at','DESC');
    }

    //
    public function image()
    {
      return $this->belongsTo('App\Image');
    }

    //
    public function notifications()
    {
      return $this->allNotifications()->where('read',false);
    }

    //
    public function allNotifications()
    {
      return $this->hasMany('App\Notification')->orderBy('created_at','DESC');
    }

    // all the orders opf the users
    public function orders()
    {
       return $this->hasMany('App\Order')->orderBy('created_at','DESC');
    }

    // all the purchases opf the users
    public function purchases()
    {
       return $this->hasMany('App\Purchase')->with('orders')->orderBy('created_at','DESC');
    }

    // orders selected
    public function shoppingCart()
    {
      return $this->orders()->where('status','selected');
    }

    // publications
    public function publications()
    {
      return $this->hasMany('App\Publication')->orderBy('created_at','desc')->where('publish_at', '<=',now());
    }

    // subastas
    public function auctions()
    {
       return $this->hasMany('App\Auction')
                   ->orderBy('created_at','desc');
    }

    // subasta abierta
    public function currentAuctions()
    {
      return $this->auctions()->without('user')->without('winner')->without('current')->where('status','open')->take(1);
    }

    public function getnumProductsAttribute()
    {
      return $this->shoppingCart->count();
    }














    /**
     * it generates the token. And we return the token + the user info
     *
     * @return Array
     */
    public function creteTokenUser()
    {
      if (auth()->user()) {
        // llamamos al trait de passport
        $tokenResult = $this->createToken('Personal Access Token');
        // recogemos el token
        $token = $tokenResult->token;
        // creamos el response
        $response = [
            'user'          => auth()->user(),
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',

        ];
        return $response;
      } else {
        $tokenResult = $this->createToken('Personal Access Token');
        return [
          'access_token' => $tokenResult->accessToken,
        ];
      }
    }

    // create a token and send an email
    public function forgetPass()
    {
      // generamos token
      $token = md5(uniqid(rand(), true));
      // creamos correo
      $data = [
        "title"         => "Cambiar contraseña de ",
        "logoInTitle"   => true,
        "text"          => "Buenas $this->name, Si desea cambiar su contraseña haz click al enlace de abajo, de lo contrario ignorae éste mail",
        "option"        => [
          'text'  =>  "Cambiar Contraseña",
          'url'   =>  url('/password?token=').$token
        ]
      ];
      sendMail::dispatch(new BasicMail($data),$this->email);
      $this->remember_token = $token;
      $this->save();
    }

    // create a token and send an email
    public function influencerEmail()
    {
      // generamos token
      $token = md5(uniqid(rand(), true));
      // creamos correo
      $data = [
        "title"         => "Usuario Influencer ",
        "logoInTitle"   => true,
        "text"          => "El usuario con nombre $this->name , y correo $this->email desea ser influencer. Identificador 00000000$this->id. ",
      ];
      sendMail::dispatch(new BasicMail($data),"influencers@onlyfet.com");
      $this->remember_token = $token;
      $this->save();
    }


    // planes a los que estas suscrito
    public function suscribedPlans()
    {
      return $this->belongsToMany('App\Plan','users_plans')->with('user')->withTimestamps()->withPivot('stripe_suscription_id')->orderBy('updated_at','DESC');
    }

    // planes que tu has creado
    public function plans()
    {
      return $this->hasMany('App\Plan');
    }

    public function notifyChangedPlan($plan)
    {
      $userplan = $plan->user;
      $months = $plan->getInterval($plan->payForEvery);
      $data = [
        "title"         => "Updated Plan ",
        "logoInTitle"   => true,
        "text"          => "Hi $this->name. Your suscription to $userplan->nickname has been updated to $plan->price € every $months months",
        "option"        => [
          'text'  =>  "$userplan->nickname",
          'url'   =>  url("/user/$userplan->nickname")
        ]
      ];
      sendMail::dispatch(new BasicMail($data),$this->email);
    }

    // on delete make the cascade
    public function delete()
    {
        $this->chats()->delete();
        $this->image()->delete();
        $this->notifications()->delete();
        return parent::delete();
    }

    public  static function tabletate($data) {
      return [
        'headers' => [
          'Identificador' =>  'id',
          'Correo'  => 'email',
          'Nombre' =>  'name',
          'Dirección de envío' => "direction",
          "Influencer" => "influencer",
          'Quiere ser influencer' => "wantToBeInfluencer",
          'nickname' => 'nickname',
          '% para el usuario' => 'percentage_for_user',
          'Idiona' => 'lang',
          'País' => 'country'
        ],
        'data'  =>  $data,
        'options' => [
          'remove'  => true,
          'edit' => true,
          'image'   => true,
        ],
        'singular' => 'user',
        'name'  => 'Usuarios',

      ];

    }


}
