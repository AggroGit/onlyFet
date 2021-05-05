<?php

namespace App\Services\User;

use App\Services\Suscriptions\SuscriptionServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Mail\BasicMail;
use App\Jobs\sendMail;
use Carbon\Carbon;
use App\Image;
use App\User;




class UserDomain
{
  protected $user;

  public function __construct(User $user) {
    $this->user = $user;
  }

  public function removeFromSuscribedPlans()
  {
    $provider = new SuscriptionServiceProvider();
    $plans = $this->user->suscribedPlans;
    foreach ($plans as $plan) {
      $provider->quitUserToPlan($this->user,$plan);
    }
  }

  public function quitUsersOfPlan()
  {
    // get the plans of the user
    $plans = $this->user->plans;
    //
    $provider = new SuscriptionServiceProvider();
    //
    foreach ($plans as $plan) {
      // get the users of the plan
      $users = $plan->usersSuscribed;
      // remove it
      foreach ($users as $user) {
        $provider->quitUserToPlan($this->user,$plan);
      }
    }
  }

}
