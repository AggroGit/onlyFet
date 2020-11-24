<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Publication as Post;
use App\Comment;
use App\Video;
use App\Image;
use App\User;
use Carbon\Carbon;



class PublicationServiceProvider
{
  public $name;

  function __construct($n = "sin nombre")
  {
    $this->name = $n;
  }

  public function saludar()
  {
    echo "Hola $this->name, estoy saludando";
  }

  // create a post
  public function createPublication(Request $request, $hastags = [])
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
    return $post;

  }

  // create a comment for a publication with request
  public function commentPublication($publication, $request)
  {
    // create the comment
    $comment = Comment::create([
        'publication_id'  => $publication->id,
        'user_id'         => auth()->user()->id,
        'comment'         => $request->comment
    ]);
    return $comment;

  }

  // add images or vídeo to the Publication
  public function uploadToPublication()
  {
    // code...
  }


}
