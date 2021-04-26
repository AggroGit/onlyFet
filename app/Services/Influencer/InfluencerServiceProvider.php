<?php

namespace App\Services\Influencer;

use App\Services\Influencer\InfluencerDomain;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;



class InfluencerServiceProvider extends InfluencerDomain
{
  // from User create an influencer
  public function registerInfluencer(Request $request=null)
  {
    if(!$this->userCanBeInfluencer())
      throw new \Exception('El usuario no puede ser influencer',4);

    $this->addDataToUser($request);
    $this->emailPendingVerification();
    $this->emailToAdminUsers();
    $this->updateUserToValidate();
    // if($request->has())
    // $this->applyDiscount();
    $this->user->save();

  }

  // the last check to be influencer
  public function checkToBeInfluencer()
  {
    return $this->have10Posts() and $this->validatedByAdmin();
  }

  public function validateInfluencer()
  {
    if(!$this->userCanBeInfluencer())
      throw new Exception('El usuario ya es influencer',4);

    $this->validate();
    $this->emailUserIsValid();


  }

  public function influencersRepositery()
  {
    return User::where([
      ['influencer',true],
      ['id','!=',$this->user->id]
    ]);
  }

  // check if the user can be validated
  public function plusPostToVerifie()
  {
    if($this->haveUploadedRequiredPosts())
      return true;

    $this->sumPostToVerifie();

    if($this->user->numUploadedPosts>=10) {
      $this->verifieNumPostsUser();
      $this->requestInfluencer();
    }

    $this->user->save();

  }

  public function requestInfluencer()
  {
    if($this->user->infleucer)
      return true;

    if($this->user->wantToBeInfluencer and $this->user->verified and $this->user->numUploadedPosts>=10 and $this->user->prices_added) {
      $this->convertToInfluencer();
    }


  }

  public function convertToInfluencer()
  {
    $this->user->influencer = true;
    $this->user->save();
    // notify via email
  }









}
