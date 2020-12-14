<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Faq;

class faqsConttroller extends Controller
{
    //
    public function getFaqs()
    {
      if(auth()->user()) {
        $lang = auth()->user()->lang?? "es";
        return $this->correct(
          Faq::where('lang',$lang)->get()
        );
      }
      return $this->correct(faq::all());

    }




}
