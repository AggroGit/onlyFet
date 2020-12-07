<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Comment;
use App\Like;
use Carbon\Carbon;

class Publication extends Model
{
    //
    protected $with = [
      'user', 'images', 'videos', 'hastags'
    ];
    //
    protected $fillable = [
      'content', 'user_id', 'publish_at'
    ];
    //
    protected $appends = [
      'numLikes', 'numComments', 'haveLiked', 'fecha',
    ];

    // likes
    public function likes()
    {
       return $this->hasMany('App\Like')
                   ->orderBy('created_at','desc');
    }

    // comments
    public function comments()
    {
       return $this->hasMany('App\Comment');
    }

    // images
    public function images()
    {
      return $this->belongsToMany('App\Image', 'publications_images');
    }

    // hastags
    public function hastags()
    {
      return $this->belongsToMany('App\Hastag', 'publications_hastags');
    }

    // videos
    public function videos()
    {
      return $this->belongsToMany('App\Video', 'publications_videos');
    }

    public function like()
    {
       if($like = $this->likes()->where('user_id',auth()->user()->id)->first()) {
         $like->delete();
         return false;
       }
       $like = new Like([
         'user_id'        => auth()->user()->id,
         'publication_id' => $this->id
       ]);
       $like->save();

       return true;

    }

    public function getNumLikesAttribute()
    {
      return $this->likes()->count();
    }


    public function getFechaAttribute()
    {
      return Carbon::create($this->publish_at)->diffForHumans();
      // return $this->created_at-
    }

    public function getHaveLikedAttribute()
    {

      if(auth()->user() and $this->likes()->where('user_id',auth()->user()->id)->first()) {
        return true;

      }
      return false;
    }

    public function getNumCommentsAttribute()
    {
      return $this->comments()->count();
    }

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function addHastags($request)
    {
      // comprobamos y añadimos
      foreach ($request as $hastag) {
        if(!$h = Hastag::where('text',$hastag)->first()) {
          // lo creamos
          $h = new Hastag(["text" => $hastag]);
          $h->save();
        }
        // lo añadimos
        $this->hastags()->save($h);
      }

    }

    public function delete()
    {
      $this->likes()->delete();
      foreach ($this->comments as $comment) {
        $comment->delete();
      }
      foreach ($this->images as $image) {
        $image->delete();
      }
      // quit but dont delete
      $this->hastags()->sync([]);
      //
      $this->images()->delete();
      $this->videos()->delete();
      return parent::delete();
    }






}
