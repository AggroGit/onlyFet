<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\StripeEvent;
use App\Jobs\sendMail;
use App\Jobs\sendMoney;
use App\Message;

use App\PayOut;
use App\User;

class StripeController extends Controller
{
    // VIEW for add card, thats only give us the id of the new card
    public function addCardView($value='')
    {
      return view('stripe.addCard')->with('noTypeScript',true);
    }

    // VIEW for pay
    public function payView()
    {
      return view('stripe.addCard')->with('noTypeScript',true);
    }

    public function ifNotCreate()
    {
      if(!auth()->user()->hasStripeId()) {
          auth()->user()->createAsStripeCustomer();
          auth()->user()->save();
      }
    }

    // pay a cuantity
    public function pay(Request $request)
    {
      // si hay un error saltamos el response con los mensajes
      if ($missings = $this->hasError($request->all(),'validation.pay')) {
        return $this->incorrect(0,$missings);
      }
      if (auth()->user()->hasDefaultPaymentMethod()) {
        $user = auth()->user();
        $cantidad = $request->amount;
        $stripeCharge = $user->cobrar($cantidad);
        return $this->correct($stripeCharge);
      } else {
        return $this->incorrect(200);
      }
    }

    // with the card id we add a card and set it by default
    public function addCard(Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.addVisa')) {
        return $this->incorrect(0,$missings);
      }
      $this->ifNotCreate();
      try {
        auth()->user()->addPaymentMethod($request->id);
        auth()->user()->updateDefaultPaymentMethod(auth()->user()->paymentMethods()->first()->id);
        return $this->correct();

      } catch (\Exception $e) {

        return $this->incorrect(206);
      }



    }

    // we return of the Stripe url and then we create the user account
    public function returnAndCreate(Request $request)
    {
      // first we have to recive the data
      if ($request->has('code') and $request->has('state')) {
        // now we have to retrive the user by the state
        if ($user = User::where('temporal_token',$request->state)->first()) {
          // if exists then we create and asociate
          if ($r = $user->createAccount($request->code)) {
            if($user->createAsAProduct()) {

            } else {
              return "ERROR";
            }
            $user->save();
            $user->refresh();
            //
            return redirect('/profile/suscriptions');
            // return $this->correct($user);
          }

        }
      }
      return redirect('/');
      // dd($request);
      // return $this->correct($request);
    }

    public function urlToCreate()
    {
      // si no tiene cuenta devolvemos para crear una
      if ($r = auth()->user()->createAccountURL() and $r !== false) {
        return $this->correct($r);
      } else {
        // sino damos el login
        return $this->incorrect(809,auth()->user()->loginLink());
      }

    }

    public function cardsList()
    {
      $this->ifNotCreate();
      return $this->correct(auth()->user()->cards());
    }

    public function sendPropina($user_id,Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.propina')) {
        return $this->incorrect(0,$missings);
      }

      if($user = User::find($user_id)) {
          if($user->canReciveMoney()) {
            $comision = 0;
            try {
              auth()->user()->cobrar($request->quantity,$comision);
            } catch (\Exception $e) {
              return $this->incorrect(201);
            }
            // si llegamos hasta aqui es que se ha cobrado
            $cant = number_format($request->quantity - $comision,2);

            $payment = PayOut::create($cant,$user,null,$request->quantity);

            //
            return $this->correct();


          } else {
            return $this->incorrect(210);
          }
          return $this->incorrect(809);
      } else {
        $this->incorrect();
      }
      return $this->incorrect();
    }





}
