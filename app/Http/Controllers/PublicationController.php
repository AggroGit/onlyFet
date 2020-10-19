<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Publication as Post;
use App\Image;
use App\User;

class PublicationController extends Controller
{
    //
    public function like(Request $request)
    {
      return $this->correct($request->post->like());
    }

    public function posts(Request $request)
    {
      return $this->correct(Post::orderBy('created_at','DESC')->paginate(50));
    }

    public function postsOfUser($user_id)
    {
      if(!$user = User::find($user_id)) {
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
      if ($missings = $this->hasError($request->all(),'validation.addPost')) {
        return $this->incorrect(0,$missings);
      }
      $post = new Post([
        "content" => $request->content,
        "user_id" => auth()->user()->id
      ]);
      $post->save();

      if($request->has('media')) {
        try {
          foreach ($request->media as $image) {
            $im = new Image();
            $im->create($image);
            $post->images()->save($im);
          }
        } catch (\Exception $e) {
          $post->delete();
          return $this->incorrect(1200);
        }
      }

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

    public function recomendUser(Request $request)
    {
      $users = User::where('name','like', "%$request->name%");
      //
      return $this->correct($users->get()->take(5));

    }


}
