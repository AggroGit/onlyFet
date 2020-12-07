<?php

namespace App\Services\Images;


use Illuminate\Support\Facades\Validator;
use App\Jobs\destroyImageJob;
use Illuminate\Http\Request;
use App\Mail\BasicMail;
use App\Jobs\sendMail;
use Carbon\Carbon;
use App\Image;
use App\User;





class ImageDomain
{
  // create image and upload
  public function upload($request,$path=null,$destroy = false)
  {
      $image = new Image();
      $image->create($request->file,$path);
      return $image;
  }

  public function programDestruction(Image $image)
  {
    // guardamos
    $image->remove_future = true;
    $image->save();
    // lanzamos el evento
    destroyImageJob::dispatch($image->id)->delay(now()->addDays(2));

  }

  public function checkifDestroy(Image $image)
  {
    return $image->remove_future;
  }

  public function destroyImage(Image $image)
  {
    $image->delete();
  }








}
