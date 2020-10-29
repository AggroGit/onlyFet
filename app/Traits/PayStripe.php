<?php





namespace App\Traits;



/**
 * Stripe Object for sending notifications
 *
 */
trait PayStripe
{

  // create the stripe object for the queries
  public function stripe()
  {
    return $stripe = new \Stripe\StripeClient(
      env('STRIPE_SECRET')
    );
  }

  public function stripeApi()
  {
    return $stripe = \Stripe\Stripe::setApiKey(
      env('STRIPE_SECRET')
    );
  }

  // all the carges of a costumer
  public function charges()
  {
    return $this->stripe()->charges->all(['customer' => $this->stripe_id]);
  }

  // return the cards of the user as stripe
  public function cards()
  {
      $cards = [];
      foreach ($this->paymentMethods() as $card) {
        $cards [] = $card->asStripePaymentMethod();
      }
      return $cards;
  }


  // create a connect account URL for recibe money
  public function createAccountURL()
  {
    // if the user has not the reciver id
    if ($this->stripe_reciver_id == null) {
      // we create a token
      $this->temporal_token = uniqid().md5(rand(1, 10) . microtime());
      // we have to return the url with some params
      $base = "https://dashboard.stripe.com/express/oauth/authorize?response_type=code&client_id=ca_HNOD9Fp5xf66fpj2ic4ZJKldwWtIHWl3&scope=read_write&"
      ."stripe_user[email]=$this->email&"
      ."stripe_user[first_name]=$this->name&"
      ."stripe_user[last_name]=$this->surnames&"
      ."stripe_user[phone_number]=$this->phone_number&"
      ."state=$this->temporal_token&";
      // save for the token
      $this->save();
      return $base;
    }
    return false;
  }

  // with the code we have to create the account for recive money
  public function createAccount($code)
  {
    $response = $this->stripeApi();
    try {
      $r = \Stripe\OAuth::token([
         'grant_type' => 'authorization_code',
         'code' => $code,
        ]);
        // if all okay we save the token
        if ($id = $r->stripe_user_id) {
          $this->stripe_reciver_id = $id;
          $this->save();
          return true;
        }
    } catch (\Exception $e) {
      return false;
    }


    return $r;

  }

  // crea en Stripe un producto
  public function createAsAProduct()
  {
    try {
      $product = $this->stripe()->products->create([
        'name'  =>  $this->name,
        'id'    =>  $this->id,
        'metadata'  => [
          'user_onlyFet_id' => $this->id,
          'description' => $this->name,
        ],
        'statement_descriptor' => "OnlyFet",
        'images' => [
          $this->image->sizes->Big?? "no-image",
        ]
      ]);
      $this->influencer = true;
    } catch (\Exception $e) {
      return false;
    }
    $this->save();
    return true;


  }


  // 🐀 Ratatouillea, A Mr Ego
  // no le gustó el plato 🍛
  public function devolver($idPago,$cantidad = null)
  {
    try {
      if(is_null($cantidad)) {
        $this->refund($idPago);
        return true;
      } else {
        // en centimos
        $cantidad = $cantidad*100;
        $this->refund($idPago,[
          'amount' => $cantidad,
        ]);
        return true;
      }
    } catch (\Exception $e) {
      return false;
    }




  }

  // return the login link of Stripe
  public function loginLink()
  {
    $stripe = $this->stripeApi();
    $r = \Stripe\Account::createLoginLink($this->stripe_reciver_id);
    return $r;
  }

  // in every app you have to specify the requeriments of the user to recive money
  public function canReciveMoney()
  {
    if ($this->stripe_reciver_id !== null) {
      return true;
    }
    return false;
  }

  // get the comision from charge
  public function getStripeCommisionFromCharge($charge)
  {
    $balance = null;
    $fee = false;
    $balance = $charge->charges->data[0]->balance_transaction;
    if($balance !== null) {
      // sacamos el objeto
      $stripe = $this->stripe();
      $balanceObject = $stripe->balanceTransactions->retrieve(
        $balance,
        []
      );
      $fee = ($balanceObject->fee)/100;
    }
    return $fee;
  }


  // equivalente al charge de Cashier pudiendo saber la comisión
  public function cobrar($amount,&$comision = false)
  {

    try {
      // pasamos el precio a int
      $price = intval($amount*100);
      // ejecutamos un cobro
      $charge = $this->charge($price, $this->defaultPaymentMethod()->id);
      //
    } catch (\Exception $e) {
      //
      return false;
    }
    // si queremos saber la comisión
    $comision = $this->getStripeCommisionFromCharge($charge->asStripePaymentIntent());
    // devolvemo el pago
    return $charge->asStripePaymentIntent();
  }

  // enviar dinero a alguien
  public function pay($amount)
  {
    if($this->canReciveMoney()) {
      try {
        $stripe = $this->stripeApi();
        $transfer = \Stripe\Transfer::create([
          "amount" => $amount*100,
          "currency" => "eur",
          "destination" => $this->stripe_reciver_id,
        ]);
        return $transfer;
      } catch (\Exception $e) {
        return false;
      }
    }


  }





}
