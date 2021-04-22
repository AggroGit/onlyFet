<?php

namespace App\Http\Controllers;

use App\Services\Publications\PublicationServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Publication as Post;
use App\Comment;
use Carbon\Carbon;
use App\Video;
use App\Image;
use App\User;

class PublicationController extends Controller
{
    protected $provider;

    public function __construct()
    {
      Carbon::setLocale(auth()->user()->lang?? 'es');
      $this->provider = new PublicationServiceProvider();

    }

    //
    public function like(Request $request)
    {
      return $this->correct($request->post->like());
    }

    public function posts(Request $request)
    {
      return $this->correct(Post::orderBy('created_at','DESC')->where('publish_at', '<=',now())->paginate(4));
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

    public function create(Request $request )
    {
      // verify data
      if ($missings = $this->hasError($request->all(),'validation.addPost')) {
        return $this->incorrect(0,$missings);
      }
      // hastag to array
      $h = $this->listToArray($request->hastags);
      $post = $this->provider->createPublication($request,$h);
      //
      return $this->correct(Post::find($post->id));

    }

    public function comment(Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.addComment')) {
        return $this->incorrect(0,$missings);
      }
      //
      $comment = $this->provider->commentPublication($request->post,$request);
      //
      return $this->correct(Comment::find($comment->id));

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
      $this->provider->uploadToPublication($request);
      return $this->correct();
    }

    public function wallOfUser($nickcname, Request $request)
    {
      if($user = User::where('nickname',$nickcname)->first()->append('is_fav')->toArray()){
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
      // imagenes de publicaciones que no se deberían ver
      $publis = Post::where('publish_at','>=',now())->pluck('id');
      if($user = User::where('nickname',$name)->first()){
        $publis_private = Post::where('publish_at','>=',now())->where('private',true)->pluck('id');
        return $this->correct([
          "user" => $user,
          "images" => Image::where('user_id',$user->id)->where('post_id','!=',null)->whereNotIn('post_id',$publis_private)->where('private',false)->orderBy('created_at','desc')->whereNotIn('post_id',$publis)->paginate(20)
        ]);
      }
      return $this->incorrect();
    }

    public function videosUser($name)
    {
      if($user = User::where('nickname',$name)->first()){
        return $this->correct([
          "user" => $user,
          "images" => Video::where('user_id',$user->id)->where('post_id','!=',null)->where('private',false)->orderBy('created_at','desc')->get()
        ]);

      }
      return $this->incorrect();
    }

    public function novedadesPosts()
    {
      $ids = auth()->user()->suscribedPlans->pluck('user_id');
      return $this->correct(Post::orderBy('created_at','DESC')->whereIn('user_id',$ids)->where('publish_at', '<=',now())->paginate(4));
    }

    public function payAndUnlock($post_id )
    {
      try {
        $p = new PublicationServiceProvider($post_id);
        return $this->correct($p->payToUnlock());
      } catch (\Exception $e) {
        return $this->incorrect($e->getCode(),$e->getMessage());
      }
    }

    public function makePrivate(Request $request, $post_id)
    {
      if ($missings = $this->hasError($request->all(),'validation.makePrivate')) {
        return $this->incorrect(0,$missings);
      }
      try {
        $p = new PublicationServiceProvider($post_id);
        return $this->correct($p->makePostPrivate($request->price));
      } catch (\Exception $e) {
        return $this->incorrect($e->getCode(),$e->getMessage());
      }
    }




}
