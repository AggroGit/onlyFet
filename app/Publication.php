<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Comment;
use App\Like;

class Publication extends Model
{
    //
    protected $with = [
      'user', 'images',
    ];
    //
    protected $fillable = [
      'content', 'user_id',
    ];
    //
    protected $appends = [
      'numLikes', 'numComments', 'haveLiked'
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
       return $this->hasMany('App\Comment')
                   ->orderBy('created_at','desc');
    }

    // images
    public function images()
    {
      return $this->belongsToMany('App\Image', 'publications_images');
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

    public function delete()
    {
      $this->likes()->delete();
      $this->comments()->delete();
      return parent::delete();
    }

    public function comment($message)
    {
      $comment = new Comment([
        'publication_id'  => $this->id,
        'user_id'         => auth()->user()->id,
        'comment'         => $message
      ]);
      //
      $comment->save();
      //
      return $comment;
    }





}
