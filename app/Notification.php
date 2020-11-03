<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    protected $with = "from";
    //
   protected $fillable = [
     'title','body','data','type','text', 'user_id', 'from_id'
   ];
   protected $appends = [
     'fecha',
   ];
   // user who belongs the notify
   public function user()
   {
     return $this->belongsTo('App\User');
   }
   // user who send the notification
   public function from()
   {
     return $this->belongsTo('App\User','from_id')->without('notifications');
   }
   //
   public function getFechaAttribute()
   {
     return $this->created_at->diffForHumans();
   }
}
