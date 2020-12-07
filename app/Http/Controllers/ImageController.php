<?php

namespace App\Http\Controllers;

use App\Services\Images\imageServiceProvider;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->provider = new imageServiceProvider();

    }
    
}
