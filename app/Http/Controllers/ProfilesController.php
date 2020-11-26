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
      $users = User::where('nickname','!=',auth()->user()->nickname?? null);
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
      // texto
      if($request->has('search') and $request->search !== null) {
        $users = $users->where('nickname','like',"%$request->search%");
      }
      return $this->correct($users->paginate(2000));

    }
}
