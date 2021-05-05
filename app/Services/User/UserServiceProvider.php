<?php

namespace App\Services\User;

use App\Services\User\UserDomain;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;



class UserServiceProvider extends UserDomain
{
  public function removeUser()
  {
    // unsuscribe all the suscriptions
    $this->removeFromSuscribedPlans();
    // if the user is influencer
    if($this->user->influencer)
      $this->quitUsersOfPlan();
    // remove the user
    $this->user->delete();
    //
    return true;
  }


}
