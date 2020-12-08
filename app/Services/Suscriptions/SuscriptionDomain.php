<?php

namespace App\Services\Suscriptions;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Publication as Post;
use App\Mail\BasicMail;
use App\Jobs\sendMail;
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

  // return a plan of user with name
  public function planWithName($user,$name)
  {
    return $user->plans()->where('payForEvery',$name)->first();
  }

  // create a new plan, if is an iupdate we have to pass the old one
  public function createNewPlan(User $user, $price, $forEvery, $old = false)
  {
    // si hay antiguo entonces creamos uno de zero
    if($old === false) {
      $plan = new Plan();
    } else {
      $plan = $old;
    }
    // create in our bd
    $plan->payForEvery = $forEvery;
    $plan->price = $price;
    $plan->user_id = $user->id;
    $plan->save();
    // create In Stripe
    // try {
      $id = $this->createPlanInStripe($plan);
      // expecificamos el antiguo
      $plan->previuous_stripe_id = $plan->stripe_tarifa_id;
      $plan->stripe_tarifa_id = $id;

    // } catch (\Exception $e) {
    //   $plan->remove();
    //   return false;
    // }
    $plan->refresh();



    $plan->save();
    // now, si es una renovación hay que cambiarle el precio a todos
    if($old !== false)
      $this->changePriceOfPlanStripe($plan);


  }



  public function changePriceOfPlanStripe($plan)
  {
    // recorremos sus usuarios y cambiamos el precio
    foreach ($plan->usersSuscribed as $user) {
      // antiguo
      // $user->subscription('default', $plan->previuous_stripe_id)->swapAndInvoice($plan->stripe_tarifa_id);
      $user->stripeApi();
      //
      $subscription = \Stripe\Subscription::retrieve($user->pivot->stripe_suscription_id);
      \Stripe\Subscription::update($user->pivot->stripe_suscription_id, [
        'cancel_at_period_end' => false,
        'proration_behavior' => 'always_invoice',
        'items' => [
          [
            'id' => $subscription->items->data[0]->id,
            'price' => $plan->stripe_tarifa_id,
          ],
        ],
      ]);

    }
  }

  // it creates a price in Stripe, return the id
  public function createPlanInStripe($plan)
  {
    return $plan->user->stripe()->prices->create([
      'unit_amount' => $plan->price*100,
      'currency' => 'eur',
      'recurring' => [
        'interval'        => 'month',
        'interval_count'  => $plan->months
      ],
      'product' => env('APP_ENV','local').$plan->user->id,
    ])->id;
  }

  public function notifyPrices(Plan $plan)
  {
    $use = $plan->user;
    $data = [
      "title"         => "Cambio de precio en su suscripción ",
      "logoInTitle"   => true,
      "text"          => "Su suscripción con $use->name ha cambiado de precio a $plan->price € cada $plan->months meses ",
    ];
    foreach ($plan->usersSuscribed as $user) {
      sendMail::dispatch(new BasicMail($data),$user->email);
    }
  }


  // add user in stripe
  public function addUserViaStripe(User $user, Plan $plan)
  {
    $percentage = 100 - $plan->user->percentage_for_user;
    $s = $user->stripeApi();
    $subscription = \Stripe\Subscription::create([
      "customer" => $user->stripe_id,
      "items" => [
        ["price" => $plan->stripe_tarifa_id],
      ],
      'application_fee_percent' => $percentage,
      "expand" => ["latest_invoice.payment_intent"],
      "transfer_data" => [
        "destination" => $plan->user->stripe_reciver_id,

      ]
    ]);
    return $subscription;
  }

  public function quitUserViaStripe(User $user, Plan $plan)
  {
    $id = $user->suscribedPlans()->where('id',$plan->id)->first()->pivot->stripe_suscription_id;
    $user->stripe()->subscriptions->cancel(
      $id,
      []
    );
  }

  public function addUserInOnlyFet(User $user, Plan $plan, String $suscription_id = null)
  {
    $plan->usersSuscribed()->attach(
      $user->id,[
        'created_at' => now(),
        'stripe_suscription_id' => $suscription_id
      ]
    );
  }

  public function isSuscribed(User $user, Plan $plan)
  {
    if($plan->usersSuscribed()->find($user->id)) {
      return true;
    }
    return false;
  }

  public function closeChatOfPlan(User $user, Plan $plan)
  {

  }

  public function notifyUnsuscriber(User $user,Plan $plan)
  {
    $plan->user->send([
      "title"   => "$user->name ha cancelado su suscripción",
      "body"    => "$user->name ha cancelado su suscripción contigo",
      "type"    => "suscription",
      "data"    => $user->id,
      "sound"   => "default",
    ]);

  }

  public function getChat(User $user,Plan $plan)
  {
    foreach ($user->chats as $chat) {
      if($chat->isUser($plan->user)) {
        return $chat;
      }
    }
    return false;
  }




}
