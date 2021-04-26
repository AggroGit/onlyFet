<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ProfilesController extends Controller
{
    //
    public function getAllUsers(Request $request)
    {
      //
      if ($missings = $this->hasError($request->all(),'validation.chooseOrder')) {
        return $this->incorrect(0,$missings);
      }
      // usuarios
      $users = User::where([
        ['nickname','!=',auth()->user()->nickname?? null],
        ['influencer',true]
      ]);
      // mis suscripciones
      if($request->orderBy == "mySuscriptions") {
        if(!auth()->user()) {
          return $this->incorrect(13);
        }
        $ids = auth()->user()->suscribedPlans->pluck('user_id');
        $users = $users->whereIn('id',$ids);
      }
      // populares
      if($request->orderBy == "populars") {
        $users = $users->orderBy('numSuscriptions','DESC');
        // $this->correct($users->get());
      }
      // nuevos
      if($request->orderBy == "news") {
        $users = $users->orderBy('created_at','desc');
      }
      // favoritos
      if($request->orderBy == "favs" and auth()->user()->users_favs !== null) {
        $ids = json_decode(auth()->user()->users_favs);
        $users = $users->whereIn('id', $ids);
      }

      // texto
      if($request->has('search') and $request->search !== null) {
        $users = $users->where('nickname','like',"%$request->search%");
      }
      return $this->correct($users->paginate(20000));

    }

    public function addFavorite($user_id)
    {
      if(!$user = User::find($user_id))
        return $this->incorrect();

      if(auth()->user()->users_favs == null) {
        $ids = [];
      } else {
        $ids = json_decode(auth()->user()->users_favs);
      }
      if (($key = array_search($user_id, $ids)) !== false) {
            unset($ids[$key]);
        } else {
          $ids[] = $user_id;
        }

      auth()->user()->users_favs = json_encode($ids);
      auth()->user()->save();

    }


}
