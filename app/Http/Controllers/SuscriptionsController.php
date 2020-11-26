<?php

namespace App\Http\Controllers;

use App\Services\Suscriptions\SuscriptionServiceProvider;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Plan;
use App\User;


class SuscriptionsController extends Controller
{
    protected $prov;
    public function __construct()
    {
      $this->provider = new SuscriptionServiceProvider();
      Carbon::setLocale(auth()->user()->lang?? 'es');
    }
    //
    // creamos los planes, con nosotros y cin stripe
    public function makePremium(Request $request)
    {
      if($request->influencer == false) {
        return ($code = $this->provider->cancelUserPremium(auth()->user(),$request) == true)?
        $this->correct(auth()->user()) : $this->incorrect($code);
      } else {
        // validate data
        if ($missings = $this->hasError($request->suscriptions,'validation.makePremium')) {
          return $this->incorrect(0,$missings);
        }
        return ($code = $this->provider->makeUserPremium(auth()->user(),$request) == true)?
        $this->correct(auth()->user()) : $this->incorrect($code);
      }


    }

    public function createPlans($request)
    {

      // si ya existen actualizamos
      // primero de Stripe
      if(auth()->user()->plans->count() !== 0) {
        foreach ($request->suscriptions as $key => $suscriptions) {
          $plan = auth()->user()->plans()->where('payForEvery',$key)->first();
          if($plan and $plan->price !== $suscriptions) {
            $plan->updateThePlans($key,$suscriptions);
          }
        }

      } else {
        foreach ($request->suscriptions as $key => $suscriptions) {
          $plan = new Plan();
          $plan->payForEvery = $key;
          $plan->price = $suscriptions;
          // $plan->oldPrice = $suscriptions;
          $plan->user_id = auth()->user()->id;
          $plan->save();
          $plan->newPlanInStripe();
        }
      }

    }

    public function getInterval($value)
    {
      switch ($value) {
        case 'month1':
          return 1;
          break;
        case 'month3':
        return 3;
        break;
        case 'month6':
        return 6;
        break;
        case 'mont12':
        return 12;
        break;
        default:
          return 12;
          break;
      }
    }

    public function suscribeToPlan($plan_id)
    {
      if($plan = Plan::find($plan_id)) {
        $r = $plan->suscribeUser(auth()->user());
        return $r? $this->correct() : $this->incorrect(207);
      } else {
        return $this->incorrect(208);
      }
    }

    // list all your suscriptions
    public function listPlans()
    {
      Carbon::setLocale(auth()->user()->lang?? 'es');
      foreach (auth()->user()->suscribedPlans as $plan) {
        if($plan->pivot->created_at !== null)
        $plan->fechaSuscri = $plan->pivot->created_at->diffForHumans();
      }
      return $this->correct(auth()->user()->suscribedPlans);
    }

    public function unsuscribePlan($plan_id)
    {

      // miremos que exista la orden
      if($plan = Plan::find($plan_id)) {
        // que esté suscrito
        $plan->cancelUser(auth()->user());
        return $this->correct();
      }
      return $this->incorrect();
      // code...
    }
}
