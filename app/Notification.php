<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    //
   protected $fillable = [
     'title','body','data','type','text'
   ];
   public function user()
   {
     return $this->belongsTo('App\User');
   }
}
