<?php

namespace App\Services\Suscriptions;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Publication as Post;
use Carbon\Carbon;
use App\Comment;
use App\Video;
use App\Image;
use App\User;
use App\Plan;




class SuscriptionDomain
{
  public function hey($value='')
  {
    echo "hey from domain";
  }

  // create the plans of the user
  public function createPlans(User $user, Request $request)
  {
    // if user already have plans update the prices, else ceate new
    if($user->plans->count() !== 0) {
      $this->updatePlansOfUser();
    } else {
      $this->createPlansOfUser();
    }
  }

  // from given request update prices of plans
  public function updatePlansOfUser(User $user, Request $request)
  {
    foreach ($request->suscriptions as $key => $suscriptions) {
      $plan = $user->plans()->where('payForEvery',$key)->first();
      if($plan and $plan->price !== $suscriptions) {
        $plan->updateThePlans($key,$suscriptions);
      }
    }
  }

  public function createPlansOfUser(User $user, Request $request)
  {
    foreach ($request->suscriptions as $key => $suscriptions) {
      $plan = new Plan();
      $plan->payForEvery = $key;
      $plan->price = $suscriptions;
      // $plan->oldPrice = $suscriptions;
      $plan->user_id = $user->id;
      $plan->save();
      $plan->newPlanInStripe();
    }
  }

}
