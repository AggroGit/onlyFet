<?php

namespace App\Services\Auction;


use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Publication as Post;
use App\Mail\BasicMail;
use App\Jobs\sendMail;
use App\Notification;
use Carbon\Carbon;
use App\Auction;
use App\Message;
use App\Chat;
use App\Image;
use App\User;





class AuctionDomain
{


  public function addAuction(Request $request, User $user = null)
  {
    $user = $user?? auth()->user();
    $auction = new Auction();

    $auction = $auction->fill($request->all());
    $auction->initial_price = $auction->current_auction = $request->price;
    $auction->user_id = $user->id;
    $date = Carbon::create($request->finish_at)->setTimezone('Europe/Madrid');
    $date = $date->format("Y.m.d H:i");
    $auction->finish_at = $date;
    $auction->save();
    return Auction::find($auction->id);
  }



  public function canBidUp(Auction $auction, User $user)
  {
    if($this->isOpen($auction) and $this->canUserBidUp($auction,$user)) {
      return true;
    }
    return false;
  }

  public function isOpen(Auction $auction)
  {
    return ($auction->status == "open")? true:false;
  }

  public function canUserBidUp(Auction $auction, User $userIn)
  {
    foreach ($auction->user->plans as $plan) {
      foreach ($plan->usersSuscribed as $user) {
        if($user->id == $userIn->id) {
          return true;
        }
      }
    }
    return false;
  }

  public function priceIsBigger(Auction $auction, $price)
  {
    return ($auction->current_auction < $price)? true:false;
  }

  public function addBidUp(Auction $auction, User $user, $price)
  {
    // guardamos la anterior
    $before = $auction;

    // modificamos la puja
    $auction->current_id = $user->id;
    $auction->current_auction = $price;
    // si el precio se ha modificado mientras modificabamos y es mayor entonces
    if($before->current_auction > $price) {
      return $before;
    }
    //
    $auction->save();
    return $auction;

  }


  public function notifyUsers($auction)
  {
    foreach ($auction->user->plans as $plan) {
      foreach ($plan->usersSuscribed as $user) {
        $user->send([
          "title"   => $plan->user->name." ha iniciado una puja",,
          "body"    => "Se puja $auction->name, a partir de $auction->initial_price €",
          "type"    => "auction",
          "data"    => $auction->id,
          "sound"   => "default",
        ]);
      }
    }
  }

  public function removeNotifications($auction)
  {
    Notification::where([
      ["type",'=','auction'],
      ["data",'=',$auction->id]
    ])->delete();
    return true;
  }

  public function notifyNobodyBidUp(Auction $auction)
  {
    $auction->user->send([
      "title"   => $auction->title,
      "body"    => "Nadie ha pujado en la subasta",
      "type"    => "auction",
      "data"    => $auction->id,
      "sound"   => "default",
    ]);
    return true;
  }

  // cobra al usuario
  public function chargeToWinner($auction)
  {
    $comision = 0;
    try {
      $auction->winner->cobrar($auction->final_price,$comision);
      $auction->stripe_comision = $comision;
      $auction->save();
    } catch (\Exception $e) {
      return false;

    }
    return true;

  }

  // marca como no pagado
  public function markAsNotPaid(Auction $auction)
  {
    $auction->status = "not_paid";
    $auction->save();
  }

  public function closeAuction(Auction &$auction)
  {
    $auction->status = "closed";
    $auction->winner_id = $auction->current_id;
    $auction->final_price = $auction->current_auction;
    $auction->save();
  }

  public function nobodyHaveBidUp(Auction $auction)
  {
    if($auction->current_auction == $auction->initial_price and $auction->current_id == null) {
      return true;
    }
    return false;
  }

  public function notifyAuctionFinished(Auction $auction, Chat $chat)
  {
    $auction->user->send([
      "title"   => $auction->title,
      "body"    => "La subasta ha finalizado con un precio de $auction->current_auction €",
      "type"    => "auction",
      "data"    => $auction->id,
      "sound"   => "default",
    ]);
    $winner = $auction->winner;
    $data = [
      "title"         => "La subasta de $auction->title ha finalizado",
      "logoInTitle"   => true,
      "text"          => "La subasta de $auction->title ha finalizado por $auction->final_price €. El usuario ganador es $winner->name.",
      "option"        => [
        'text'  =>  "Chat con el ganador",
        'url'   =>  url("chats/$chat->id")
      ]
    ];
    sendMail::dispatch(new BasicMail($data),$auction->user->email);
  }

  public function notifyAuctionWinner(Auction $auction, Chat $chat)
  {
    $auction->winner->send([
      "title"   => "Has ganado la subasta de $auction->title",
      "body"    => "La subasta ha finalizado con un precio de $auction->current_auction €",
      "type"    => "auction",
      "data"    => $auction->id,
      "sound"   => "default",
    ]);
    $winner = $auction->winner;
    $data = [
      "title"         => "Has ganado la subasta de OnlyFet",
      "logoInTitle"   => true,
      "text"          => "Enhorabuena, has ganado la subasta de OnlyFet",
      "option"        => [
        'text'  =>  "Abrir Chat",
        'url'   =>  url("chats/$chat->id")
      ]
    ];
    sendMail::dispatch(new BasicMail($data),$auction->winner->email);
  }

  public function isFinished(Auction $auction)
  {
    return ($auction->status == "closed")? true:false;
  }


  public function sendAuctionToChat(Auction $auction, Chat $chat)
  {
    // creamos el mensaje
    $message = new Message();
    $message->message = "";
    $message->chat_id = $chat->id;
    $message->auction_id = $auction->id;
    $message->user_id = $auction->user_id;
    //
    $message->save();
  }

  public function canUploadImagesToAuction(Auction $auction, Image $image,$user = null)
  {
    $user = $user?? auth()->user();
    return ($user->id == $auction->user->id);
  }


}
