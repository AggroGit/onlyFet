<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Jobs\sendMoney;
use App\PayOut;

class PayOut extends Model
{
    //
    protected $table = "pay_outs";

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function from()
    {
      return $this->belongsTo('App\User','from_user');
    }

    // creamos un pago
    public static function create($cantidad,$to,$from = null)
    {
      $new = new PayOut();
      $new->from_user = $from?? auth()->user()->id;
      $new->user_id = $to->id;
      $new->price_sended = $cantidad;
      $new->money_send_at = now()->addDays(8);
      $new->save();
      sendMoney::dispatch(PayOut::find($new->id));
      $new->notiMoneySended($to,$from,$cantidad);
      //

    }



    public function pagar()
    {
      $r = $this->user->pay($this->quantity);
      if($r !== false) {
        $this->sended = true;
        $this->stripe_payout_id = $r->id;
      } else {
        $this->failed = true;
      }
      $this->save();
    }

    public static function notiMoneySended($to,$from,$cantidad,$mensaje)
    {
      $to->send([
        "title"   => "Has recibido $cantidad € de $from->name",
        "body"    => $mensaje?? "Te ha enviado una propina",
        "type"    => "propina",
        "data"    => null,
        "sound"   => "default",

      ]);
      $from->send([
        "title"   => "Has enviado $cantidad € a $to->name",
        "body"    => "Has enviado tu propina",
        "type"    => "propina",
        "data"    => null,
        "sound"   => "default",

      ]);
    }



}
