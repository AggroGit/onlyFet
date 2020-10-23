<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $with="user";

    protected $fillable = [
      'user_id', 'publication_id', 'comment'
    ];

    protected $appends = [
      'fecha'
    ];

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function publication()
    {
      return $this->belongsTo('App\Publication');
    }

    public function getFechaAttribute()
    {
      return $this->created_at->diffForHumans();
    }

    //
    public static function boot()
    {
        parent::boot();
        //
        self::created(function($model){
            //
            if($model->publication->user->id !== auth()->user()->id) {
              $model->publication->user->send([
                "title"   => auth()->user()->name,
                "body"    => "Ha comentado tu publicación",
                "type"    => "publication",
                "data"    => $model->publication->id,
                "sound"   => "default"
              ]);
            }

        });
    }
}
