<?php

namespace App\Jobs;

use App\Services\Images\imageServiceProvider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Image;

class destroyImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $image_id = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($image_id)
    {
      $this->image_id = $image_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      if($image = Image::find($this->image_id)) {
        $service =  new imageServiceProvider();
        $service->deleteImage($image);
      }
    }
}
