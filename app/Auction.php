<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;


class Auction extends Model
{
    protected $with = ["images",'user','winner','current'];
    //
    protected $appends =['remainingSeconds'];
    //
    protected $fillable = [
      'initial_price', 'final_price', 'title', 'description'
    ];
    // owner user
    public function user()
    {
      return $this->belongsTo('App\User')->without('plans');
    }

    // current auction
    public function current()
    {
      return $this->belongsTo('App\User','current_id');
    }

    // winner of the auction
    public function winner()
    {
      return $this->belongsTo('App\User','winner_id');
    }

    // images
    public function images()
    {
      return $this->belongsToMany('App\Image', 'auctions_images');
    }

    public function getremainingSecondsAttribute()
    {
      return Carbon::parse(now())->diffInSeconds($this->finish_at?? now(), false);
    }
}
