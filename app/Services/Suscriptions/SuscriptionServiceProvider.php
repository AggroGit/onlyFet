<?php

namespace App\Services\Suscriptions;

use App\Services\Suscriptions\SuscriptionDomain;

use App\Services\Influencer\InfluencerServiceProvider;
use App\Services\Chats\ChatsServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Publication as Post;
use Carbon\Carbon;
use App\Comment;
use App\Video;
use App\Image;
use App\User;
use App\Plan;




class SuscriptionServiceProvider extends SuscriptionDomain
{

  // make user premium, only if the user can accept money from stripe
  public function makeUserPremium(User $user,Request $request) : int
  {
    if($user->wantToBeInfluencer and $user->verified == false) {
      return 4;
    }
    // if user dont have plans create, else update
    if($user->plans->count() !== 0)
      $this->updatePlansOfUser($user,$request->suscriptions);
     else
      $this->createPlansOfUser($user,$request->suscriptions);

    $user->prices_added = true;
    $user->save();
    $user->refresh();
    // call the request influencer
    $provider = new InfluencerServiceProvider($user);
    $provider->requestInfluencer();
    return true;
  }



  public function cancelUserPremium(User $user,Request $request)
  {
    $user->influencer = false;
    $user->save();
    return true;
  }

  public function updatePlansOfUser(User $user,$suscriptions)
  {
    // recorremos las suscriociones
    foreach ($suscriptions as $name => $price) {
      // cogemos el plan
      if($plan = $this->planWithName($user,$name)) {
        // solo si su precio es diferente, sino es absurdo
        if($plan->price!==$price) {
          // creamos el nuevo plan, a partir del otro
          $this->createNewPlan($user,$price,$name,$plan);
          // notificamos los cambios a los usuarios afectados
          $this->notifyPrices($plan);
        }
      } else {
        return false;
      }
    }
    return true;
  }

  public function createPlansOfUser(User $user,$suscriptions)
  {
    // recorremos las suscriociones
    foreach ($suscriptions as $name => $price) {
      // creamos el nuevo plan
      $this->createNewPlan($user,$price,$name);
    }

    return true;
  }

  public function addUserToPlan(User $user, Plan $plan)
  {
    if(!$this->isSuscribed($user,$plan)) {
      //
      $sus = $this->addUserViaStripe($user,$plan);
      $this->addUserInOnlyFet($user,$plan,$sus->id);
      // create chats
      $providerChats = new ChatsServiceProvider();
      $providerChats->createChat(array($user,$plan->user));
      return true;

    }
    return false;
  }

  public function quitUserToPlan(User $user, Plan $plan)
  {
    if($this->isSuscribed($user,$plan)) {
      $this->quitUserViaStripe($user,$plan);
      $user->suscribedPlans()->detach($plan->id);
      $this->notifyUnsuscriber($user,$plan);
      $this->closeChatOfPlan($user,$plan);
      return true;
    }
    return false;
  }

  public function closeChatOfPlan($user,$plan)
  {
    if($chat = $this->getChat($user,$plan)) {
      $chat->delete();
    }
  }





}
