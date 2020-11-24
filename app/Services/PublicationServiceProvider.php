<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Publication as Post;
use Carbon\Carbon;
use App\Comment;
use App\Video;
use App\Image;
use App\User;




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
    $post = $request->post;
    $image = ['file'=> 'image:mimes:jpg,jpeg,png'];
    $video = ['file'=> 'video:mimes:mp4,mov'];

    $validator = Validator::make($request->all(),$image);
    if($validator->fails()) {
      // probamos vídeo
      $video = new Video();
      $video->create($request->file,"video/$post->id");
      $video->user_id = $post->user_id?? null;
      $video->post_id = $post->id;
      $video->save();
      $post->videos()->save($video);

    } else {
      // imagen
      $image = new Image();
      $image->create($request->file,"image/$post->id");
      $image->user_id = $post->user_id?? null;
      $image->post_id = $post->id;
      $image->save();
      $post->images()->save($image);
    }
  }


}
