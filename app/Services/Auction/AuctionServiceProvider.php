<?php

namespace App\Services\Auction;

use App\Services\Images\imageServiceProvider;
use App\Services\Chats\ChatsServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Services\Auction\AuctionDomain;
use App\Jobs\FinishAuctionJob;
use Illuminate\Http\Request;
use App\Events\BidUpEvent;
use Carbon\Carbon;
use App\Auction;
use App\Comment;
use App\Image;
use App\User;
use App\Plan;





class AuctionServiceProvider extends AuctionDomain
{

  public function createAuction(User $user,Request $request)
  {
    // si es influencer
    if(!$user->influencer)
    return 4;

    // no hay una puja ya actualmente
    if($this->AuctingNow($user)) {
      return 1601;
    }


    // create the auction
    $auction = $this->addAuction($request);
    // avisamos a los usuarios
    $this->notifyUsers($auction);
    // lanzamos el evento de cierre
    FinishAuctionJob::dispatch($auction->id)->delay(Carbon::parse($request->finish_at));
    //
    return $auction;

  }

  public function AuctingNow($user)
  {
    if($user->currentAuctions()->first()) {
      return true;
    }
    return false;
  }

  public function BidUp(Auction $auction, User $user, $price)
  {
    // si el usuario puede
    if(!$this->canBidUp($auction,$user)) {
      return 1602;
    }
    if(!$this->priceIsBigger($auction,$price)) {
      return 1603;
    }
    // ponemos el precio
    $auction = $this->addBidUp($auction,$user,$price);
    $auction->refresh();
    // lanzamos el evento de actualizacion
    Broadcast(new BidUpEvent($auction));
    //
    return true;

  }

  // finalizar la subasta
  public function finishAuction(Auction $auction)
  {
    // si ya esta finalizada
    if($this->isFinished($auction))
    return false;

    // cerramo
    $this->closeAuction($auction);
    //
    $auction->refresh();
    // evento
    Broadcast(new BidUpEvent($auction));
    // si nadie ha apostado
    if($this->nobodyHaveBidUp($auction))
      return $this->notifyNobodyBidUp($auction);
    // collect money of the auction
    if(!$this->chargeToWinner($auction)) {
      $this->markAsNotPaid($auction);
      return false;
    }
    $auction->refresh();
    // get the chat
    $chatProvider = new ChatsServiceProvider();
    $chat = $chatProvider->giveMeorCreateChatWith($auction->user,$auction->winner);
    echo "echo 1;";
    // notify the user
    $this->notifyAuctionFinished($auction,$chat);
    // notify the winner
    $this->notifyAuctionWinner($auction,$chat);
    // send to the chat
    $this->sendAuctionToChat($auction,$chat);
    // remove the notifications
    $this->removeNotifications($auction);
  }

  public function uploadImageToAuction($request,$auction)
  {
    $provi = new imageServiceProvider();
    $image = $provi->uploadImage($request,"auction");
    $auction->images()->save($image);
    return true;
  }

  public function getAuction($auction_id,User $user)
  {
    if(!$auction = Auction::find($auction_id))
      return 1604;


    if($auction->user->id == $user->id)
      return Auction::find($auction_id);

    if(!$this->canUserBidUp($auction,$user))
      return 1602;



    return Auction::find($auction_id);


  }






}
