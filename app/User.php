<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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

    protected $with=['image'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','surnames', 'email', 'password', 'device_token','social_name','social_token'
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

    //
    public function allChats()
    {
      return $this->belongsToMany('App\Chat','chats_users')->orderBy('created_at','DESC');
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
       return $this->hasMany('App\Order');
    }

    // orders selected
    public function shoppingCart()
    {
      return $this->orders()->where('status','selected');
    }

    // publications
    public function publications()
    {
      return $this->hasMany('App\Publication')->orderBy('created_at','desc');
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
      sendMail::dispatch(new BasicMail($data),$this->email);
      $this->remember_token = $token;
      $this->save();
    }

    // on delete make the cascade
    public function delete()
    {
        $this->chats()->delete();
        $this->image()->delete();
        $this->notifications()->delete();
        return parent::delete();
    }
}
