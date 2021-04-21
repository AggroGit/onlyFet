<?php

namespace App\Services\Propina;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\PayOut;
use App\User;



class PropinaDomain
{
  protected $userFrom,$userTo,$quantity,$payOut;

  public function __construct(User $userFrom, User $userTo, $quantity,$type="propina")
  {
    $this->userFrom = $userFrom;
    $this->userTo = $userTo;
    $this->quantity = $quantity;
    // load data payout
    $this->payOut = new PayOut();
      $this->payOut->user_id = $userTo->id;
      $this->payOut->from_user = $userFrom->id;
      $this->payOut->quantity = $quantity;
      $this->payOut->type = $type;

  }

  public function canSendMoney($user=null)
  {
    $user = $user?? $this->userFrom;
    return true;
  }

  public function canReciveMoney($user = null)
  {
    $user = $user?? $this->userTo;
    return $user->canReciveMoney();
  }

  public function chargeToPayOut($charge,$comision)
  {
    if($comision == 0)
      throw new \Exception("Error Stripe Comision 0", 1);

    $this->payOut->stripe_charge_id = $charge->id;
    $this->payOut->stripe_comision = $comision;
    $this->payOut->money_send_at = now()->addDays(8);

  }


  public function loadComisions()
  {
    $realGetFromUser = $this->payOut->quantity - $this->payOut->stripe_comision;
    $this->payOut->only_comision = number_format(((100-$this->userTo->percentage_for_user)/100) * $realGetFromUser,2);
    $this->payOut->price_sended = number_format($realGetFromUser - $this->payOut->only_comision,2);
    $this->payOut->influencer_comision = $this->payOut->user->percentage_for_user;
  }

  public static function sendMoneyToUser($payOut)
  {
    // if(!$id = $payOut->userTo->pay($this->payOut->price_sended)) {
    //   $payOut->failed = true;
    //   $payOut->save();
    //   throw new \Exception("Error To send Money", 1);
    // }
    //
    // //
    // $payOut->stripe_payout_id = $id;
    // $payOut->sended = true;

  }

  public function sendPayOutNotifications($mensaje=null)
  {
    $from = $this->userFrom;
    $to = $this->userTo;
    $payout = $this->payOut;

    $this->userTo->send([
      "title"   => "Has recibido $payout->price_sended € de ".$from->name?? "un usuario",
      "body"    => $mensaje?? "Te ha enviado una propina",
      "type"    => "propina",
      "data"    => "$from->nickname",
      "sound"   => "default",

    ]);
    $this->userFrom->send([
      "title"   => "Has enviado una propina de $payout->quantity € a $to->name",
      "body"    =>  $mensaje?? "Has enviado tu propina. ",
      "type"    => "propina",
      "data"    => "$to->nickname",
      "sound"   => "default",

    ]);
  }






}
