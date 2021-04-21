<?php

namespace App\Services\Influencer;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Mail\BasicMail;
use App\Jobs\sendMail;
use Carbon\Carbon;
use App\Image;
use App\User;




class InfluencerDomain
{
  protected $user;

  public function __construct(User $user) {
    $this->user = $user;
  }

  // conditions to be influencer
  public function userCanBeInfluencer()
  {
    return !$this->user->influencer;
  }

  public function addDataToUser(Request $request)
  {
    $this->user->birthday = Carbon::parse($request->birthday);
    $this->user->bank_account = $request->bank_account;
    if($request->has('email'))
      $this->user->email = $request->email;

  }



  public function emailPendingVerification($value='')
  {
    $data = [
      "title"         => "Influencer OnlyFet",
      "logoInTitle"   => true,
      "text"          => __('emails.pendingInfluencer',['name' => $this->user->name]),
      "option"        => [
        'text'  =>  __('emails.goTo'),
        'url'   =>  url("/")
      ]
    ];
    sendMail::dispatch(new BasicMail($data),$this->user->email);
  }

  public function updateUserToValidate()
  {
    $this->user->wantToBeInfluencer = true;
    $this->user->influencer = false;
  }

  public function have10Posts()
  {
    return ($this->user->publications()->count()>=10)?
    true:false;
  }

  public function validatedByAdmin()
  {
    return $this->user->verified;
  }

  public function validate()
  {
    $this->user->verified = true;
    $this->user->stripe_created = true;
    $this->user->save();
  }

  public function haveUploadedRequiredPosts()
  {
    if($this->user->updoadRequiredPics)
      return true;
  }

  public function sumPostToVerifie()
  {
    $this->user->numUploadedPosts = $this->user->numUploadedPosts+1;
  }

  public function verifieNumPostsUser()
  {
    $this->user->updoadRequiredPics = true;
  }

  public function emailUserIsValid()
  {
    $data = [
      "title"         => "OnlyFet",
      "logoInTitle"   => true,
      "text"          => __('emails.validated',['name' => $this->user->name]),
      "option"        => [
        'text'  =>  __('emails.addPrices'),
        'url'   =>  url("/profile/suscriptions")
      ]
    ];
    sendMail::dispatch(new BasicMail($data),$this->user->email);
  }

  public function emailToAdminUsers()
  {
    $data = [
      "title"         => "OnlyFet",
      "logoInTitle"   => true,
      "text"          => "Un nuevo usuario quiere ser Influencer.Validación pendiente",
      "option"        => [
        'text'  =>  "Validación de usuarios",
        'url'   =>  url("/admin/validateUsers")
      ]
    ];
    $users = User::where("admin",true)->get();
    //
    foreach ($users as $user) {
      sendMail::dispatch(new BasicMail($data),$user->email);
    }
  }














}
