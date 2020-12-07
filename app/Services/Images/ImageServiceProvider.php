<?php

namespace App\Services\Images;

use Illuminate\Support\Facades\Validator;
use App\Services\Images\ImageDomain;
use App\Jobs\FinishAuctionJob;
use Illuminate\Http\Request;
use App\Events\BidUpEvent;
use Carbon\Carbon;
use App\Auction;
use App\Comment;
use App\Image;
use App\User;
use App\Plan;





class imageServiceProvider extends ImageDomain
{
  // upload single image
  public function uploadImage(Request $request,$name=null,$destroy = false)
  {
    $image = $this->upload($request,$name,$destroy);
    if($destroy) {
      $this->programDestruction($image);
    }
    return $image;
  }

  public function deleteImage(Image $image)
  {
    if($this->checkifDestroy($image)) {
      $this->destroyImage($image);
      return true;
    }
    return false;
  }

  public function dontDestroy(Image $image)
  {
    $image->remove_future = false;
    $image->save();
  }
















}
