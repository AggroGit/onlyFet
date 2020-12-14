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
        echo "esta logeado";
        $lang = auth()->user()->lang?? "es";
        echo "$lang";
        return $this->correct(
          Faq::where('lang',$lang)->get()
        );
      }
      return $this->correct(faq::all());

    }




}
