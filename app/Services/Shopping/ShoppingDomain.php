<?php

namespace App\Services\Shopping;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Publication as Post;
use App\Mail\BasicMail;
use App\Jobs\sendMail;
use Carbon\Carbon;
use App\Comment;
use App\Video;
use App\Image;
use App\User;
use App\Plan;




class ShoppingDomain
{
  public function hey($value='')
  {
    echo "hey from domain";
  }






}
