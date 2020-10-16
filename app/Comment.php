<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
      'user_id', 'publication_id', 'comment'
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
        //
        self::created(function($model){
            //
            $model->publication->user->send([
              "title"   => auth()->user()->name,
              "body"    => "Ha comentado tu publicación",
              "type"    => "publication",
              "data"    => $model->id,
              "sound"   => "default"
            ]);
        });
    }
}
