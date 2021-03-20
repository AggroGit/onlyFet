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

  function __construct(int $post_id = null)
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
}
