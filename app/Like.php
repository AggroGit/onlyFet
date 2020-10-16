<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\PublicationEvent;


class Like extends Model
{
    //
    protected $fillable = [
      'user_id', 'publication_id'
    ];

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function publication()
    {
      return $this->belongsTo('App\Publication');
    }


    //
    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            // ... code here
        });

        self::created(function($model){
          try {
            broadcast(new PublicationEvent($model->publication));
          } catch (\Exception $e) {


          }
            //
            $model->publication->user->send([
              "title"   => auth()->user()->name,
              "body"    => "Le ha dado like a tu foto",
              "type"    => "publication",
              "data"    => $model->id,
              "sound"   => "default"
            ]);
        });

        self::updating(function($model){
            // ... code here
        });

        self::updated(function($model){

        });

        self::deleting(function($model){
            // ... code here
        });

        self::deleted(function($model){
            // ... code here
        });
    }
}
