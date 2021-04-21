<?php

namespace App\Services\Publications;

use App\Services\Influencer\InfluencerServiceProvider as influencerService;
use App\Services\Publications\PublicationDomain;
use App\Services\Propina\PropinaServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Publication as Post;
use Carbon\Carbon;
use App\Comment;
use App\Video;
use App\Image;
use App\User;




class PublicationServiceProvider extends PublicationDomain
{

  public function saludar()
  {
    echo "Hola $this->name, estoy saludando";
  }

  // create a post
  public function createPublication(Request $request, $hastags = [])
  {
    $this->create($request,$hastags);
    $this->publication->addHastags($hastags);
    // check the reqs of influencer
    $influencer = new influencerService(auth()->user());
    $influencer->plusPostToVerifie();
    //
    if($request->private == true) {
      $this->makePostPrivate($request->price);
    }

    // return the created post
    return $this->publication;

  }

  public function makePostPrivate($price)
  {
    $this->privatePost($price);
    $this->publication->save();
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
  public function uploadToPublication(Request $request)
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

  public function payToUnlock()
  {
    if(!$this->checkIfCanPayUnlock())
      throw new \Exception("User Cannot Unlock this content", 2);
    // creamos un pay out de tipo publucation
    $propi = new PropinaServiceProvider(auth()->user(),$this->publication->user,$this->publication->price,"publication_private");
    //
    $propi->sendPropina();
    // ahora le agregamos como usario que sí que puede usar la publicacion
    $this->unlockForUser();
    //
    $this->publication->save();
    //
    return $this->publication;

  }




}
