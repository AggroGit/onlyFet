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
    public static function create($cantidad,$to,$from = null,$original)
    {
      $from = $from?? auth()->user();
      $new = new PayOut();
      $new->from_user = $from->id?? auth()->user()->id;
      $new->user_id = $to->id;
      $new->price_sended = $cantidad;
      $new->money_send_at = now()->addDays(8);
      $new->save();
      sendMoney::dispatch(PayOut::find($new->id));
      $new->notiMoneySended($to,$from,$cantidad,null,$original);
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

    public static function notiMoneySended($to,$from,$cantidad,$mensaje=null,$original)
    {
      $to->send([
        "title"   => "Has recibido $cantidad € de ".$from->name?? "un usuario",
        "body"    => $mensaje?? "Te ha enviado una propina",
        "type"    => "propina",
        "data"    => "$from->nickname",
        "sound"   => "default",

      ]);
      $from->send([
        "title"   => "Has enviado una propina de $original € a $to->name",
        "body"    => "Has enviado tu propina",
        "type"    => "propina",
        "data"    => "$to->nickname",
        "sound"   => "default",

      ]);
    }



}
