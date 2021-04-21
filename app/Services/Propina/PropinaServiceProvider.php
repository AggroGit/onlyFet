<?php

namespace App\Services\Propina;

use App\Services\Propina\PropinaDomain;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Jobs\sendMoney;
use App\PayOut;
use App\User;





class PropinaServiceProvider extends PropinaDomain
{
  public function sendPropina($message=null)
  {
    if(!$this->canSendMoney())
      throw new \Exception("User cannot send money", 90);

    if(!$this->canReciveMoney())
      throw new \Exception("User cannot recive money", 10);

    $this->chargeToUser();

    $this->loadComisions();

    $this->sendPayOutNotifications($message);

    $this->payOut->message = $message;

    $this->payOut->save();

    sendMoney::dispatch($this->payOut)->delay(now()->addDays(8));
    // dd($this->payOut);
    return true;

  }

  public static function payToUser(PayOut $payOut)
  {
    if($payOut->sended)
      throw new \Exception("Payout Already sended", 1);

    if(!$payment = $payOut->user->pay($payOut->price_sended)) {
      $payOut->failed = true;
      $payOut->save();
      throw new \Exception("Error To send Money", 1);
    }

    //
    $payOut->stripe_payout_id = $payment->id;
    $payOut->sended = true;
    //
    $payOut->save();

  }



  public function chargeToUser($quantity = null, $user = null)
  {
    $user = $user?? $this->userFrom;
    $quantity = $quantity?? $this->quantity;
    $comision = 0;

    if(!$charge = $user->cobrar($quantity,$comision))
      throw new \Exception("Error to charge to User", 1);
    $this->chargeToPayOut($charge,$comision);

  }

  public static function propinasRepositery(User $user=null)
  {
    $user = $user?? auth()->user();
    return PayOut::with('from')->where('user_id',$user->id);
  }


}
