<?php

namespace App\Http\Controllers;
//
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use FFMpeg\Filters\Video\VideoFilters;
use Illuminate\Http\Request;
use App\Video;


class TestController extends Controller
{
    //
    public function video(Request $request)
    {
      // // dd($request);
      // return $this->correct($request->all());
      $video = new Video();
      $video->create($request->video);
      return $this->correct($video);

    }
}
