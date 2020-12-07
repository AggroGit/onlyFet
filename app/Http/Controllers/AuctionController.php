<?php

namespace App\Http\Controllers;


use App\Services\Auction\AuctionServiceProvider;
use App\Services\Images\imageServiceProvider;
use Illuminate\Http\Request;
use App\Auction;

class AuctionController extends Controller
{
  public function __construct()
  {
    $this->provider = new AuctionServiceProvider();
  }
  //
  public function createAuction(Request $request)
  {
    // validation
    if($missings = $this->hasError($request->all(),'validation.createAuction')) {
      return $this->incorrect(0,$missings);
    }
    //
    $code = $this->provider->createAuction(auth()->user(),$request);
    //
    return is_numeric($code)?
    $this->incorrect($code) : $this->correct($code);


  }

  public function BidUp($auction_id, Request $request)
  {
    // validation
    if($missings = $this->hasError($request->all(),'validation.bidup')) {
      return $this->incorrect(0,$missings);
    }
    if($auction = Auction::find($auction_id)) {
      return ($code = $this->provider->BidUp($auction,auth()->user(),$request->price)) === true?
      $this->correct($code):$this->incorrect($code);
    }
    return $this->incorrect();
  }

  // upload file
  public function uploadForDestroy(Request $request)
  {
    if($missings = $this->hasError($request->all(),'validation.uploadImage')) {
      return $this->incorrect(0,$missings);
    }
    $improv = new imageServiceProvider();
    return $this->correct($improv->uploadImage($request,"auctions",true));
  }

  public function updateimage(Request $request, $auction_id)
  {
    if($auction = Auction::find($auction_id)) {
      $this->provider->uploadImageToAuction($request,$auction);
      return $this->correct();
    }
    return $this->incorrect();
  }

  public function getAuction($auction_id)
  {
    $code = $this->provider->getAuction($auction_id,auth()->user());

    return is_numeric($code)?
    $this->incorrect($code) : $this->correct($code);
  }
}
