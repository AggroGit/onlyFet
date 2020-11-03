<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Publication as Post;
use Carbon\Carbon;
use App\Video;
use App\Image;
use App\User;

class PublicationController extends Controller
{
    public function __construct()
    {
      Carbon::setLocale(auth()->user()->lang?? 'es');
        // $this->middleware('auth');
    }

    //
    public function like(Request $request)
    {
      return $this->correct($request->post->like());
    }

    public function posts(Request $request)
    {
      return $this->correct(Post::orderBy('created_at','DESC')->paginate(4));
    }

    public function remove(Request $request)
    {
      return $this->correct($request->post->delete());
    }

    public function postsOfUser($user_id)
    {
      if(!$user = User::with('plans')->find($user_id)) {
        return $this->incorrect(3);
      }
      return $this->correct($user->publications()->paginate(50));
    }

    public function see(Request $request)
    {
      return $this->correct($request->post);
    }

    public function create(Request $request)
    {
      // $this->quitNulls($request);
      //
      if($request->has('publish_at') and $request->publish_at !== null) {
        $c = Carbon::create($request->publish_at)->setTimezone('Europe/Madrid');
        $c = $c->format("Y.m.d H:i");
      }


      //
      if ($missings = $this->hasError($request->all(),'validation.addPost')) {
        return $this->incorrect(0,$missings);
      }
      //
      $post = new Post([
        "content" => $request->content,
        "user_id" => auth()->user()->id,
        "publish_at" => $c?? null,
      ]);
      $post->save();
      $h = $this->listToArray($request->hastags);
      $post->addHastags($h);
      //
      return $this->correct(Post::find($post->id));

    }

    public function comment(Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.addComment')) {
        return $this->incorrect(0,$missings);
      }
      if($com = $request->post->comment($request->comment)) {
        return $this->correct($com);
      } else {
        $this->incorrect();
      }

    }

    public function comments(Request $request)
    {
      return $this->correct($request->post->comments);
    }

    public function recomendUser(Request $request)
    {
      $users = User::where('name','like', "%$request->name%");
      //
      return $this->correct($users->get()->take(5));

    }

    public function upload($post_id, Request $request)
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
      return $this->correct();
    }

    public function wallOfUser($nickcname, Request $request)
    {
      if($user = User::where('nickname',$nickcname)->first()){
        return $this->correct([
          "user"  => $user
          // "posts" => $user->publications
        ]);
      } else {
        return $this->incorrect(3);
      }

    }

    public function image($post_id,$name,Request $request)
    {
      $post = Post::find($post_id);
      if($post->user->canSee) {
        if($image = Image::where('name',$name)->first()){
          if($image->hasPermision(auth()->user())) {
            return $this->correct($image);
          }
        }
      }

      return $this->incorrect();
    }

    public function imagesUser($name)
    {
      if($user = User::where('nickname',$name)->first()){
        return $this->correct([
          "user" => $user,
          "images" => Image::where('user_id',$user->id)->paginate(20)
        ]);

      }
      return $this->incorrect();
    }

    public function videosUser($name)
    {
      if($user = User::where('nickname',$name)->first()){
        return $this->correct([
          "user" => $user,
          "images" => Video::where('user_id',$user->id)->get()
        ]);

      }
      return $this->incorrect();
    }




}
