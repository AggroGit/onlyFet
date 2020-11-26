<?php

namespace App\Services\Suscriptions;

use App\Services\Suscriptions\SuscriptionDomain;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Publication as Post;
use Carbon\Carbon;
use App\Comment;
use App\Video;
use App\Image;
use App\User;




class SuscriptionServiceProvider extends SuscriptionDomain
{

  // make user premium, only if the user can accept money from stripe
  public function makeUserPremium(User $user,Request $request) : int
  {
    // check the user can recibe money
    if($user->stripe_reciver_id == null)
    return 4;
    // create the plans
    $this->createPlans($user,$request);
    // make the user influencer
    $user->influencer = true;
    $user->save();

    //
    return true;

  }

  // from the request swith the user to be influencer or not
  public function switchMakePremium(User $user,Request $request) :integer
  {
    if($request->influencer == true)
      return $this->makeUserPremium($user,$request);
    else
      return $this->cancelUserPremium($user,$request);

  }


  // BUG: NO DEBERIA SER ASI, DEBERÍA ESTAR EN DOMINIO
  public function cancelUserPremium(User $user,Request $request)
  {
    $user->cancelInfluencer();
    return true;
  }


}
