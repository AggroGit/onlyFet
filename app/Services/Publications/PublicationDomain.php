<?php

namespace App\Services\Publications;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Publication as Post;
use Carbon\Carbon;
use App\Comment;
use App\Video;
use App\Image;
use App\User;




class PublicationDomain
{
  protected $publication;

  function __construct($post_id = null)
  {
    $this->publication = Post::find($post_id);
  }

  public function create(Request $request, $hastags = [])
  {
    // check if is for later or now
    if($request->has('publish_at') and $request->publish_at !== null) {
      $c = Carbon::create($request->publish_at)->setTimezone('Europe/Madrid');
      $c = $c->format("Y.m.d H:i");
    }
    // create the publication
    $post = Post::create([
      "content" => $request->content,
      "user_id" => auth()->user()->id,
      "publish_at" => $c?? now(),
    ]);
    $post->addHastags($hastags);
    // return the created post
    $this->publication = $post;
  }

  public function privatePost($price)
  {
    $this->publication->price = $price;
    $this->publication->private = true;
  }

  public function checkIfCanPayUnlock()
  {
    return !$this->publication->canSee;
  }

  public function unlockForUser($user = null)
  {
    $user = $user?? auth()->user();
    $users = $this->publication->users_unlocked;
    if($users !== null) {
      $ids = json_decode($users);
      $ids[] = $user->id;
    } else {
      $ids = array($user->id);
    }
    $this->publication->users_unlocked = json_encode($ids);
  }

  public function checkIfCanBeLocked()
  {
    return (auth()->user()->id == $this->publication->user->id and $this->publication->private);
  }


}
