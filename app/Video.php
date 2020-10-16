<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use FFMpeg\Filters\Video\VideoFilters;
use App\Jobs\jobCompressVideo;
use FFMpeg;

class Video extends Model
{
    protected $appends = [
      'Path'
    ];

    //
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }

    public function getPathAttribute()
    {
      $base = "";
      if($this->base == "storage") {
        $base = "storage";
      }
      // folder
      $folder = $this->url;
      // subfolder
      $name = $this->name;
      // format
      $format = $this->format;
      // file name
      $file = $this->exported? "exported":"original";
      //
      return url("$base/$folder/$name/$file.$format");
    }

    public function create($video, $path=null, $public=false)
    {
      // primero de todo creamos el token
      $this->name = is_null($this->name)?
      $this->generateToken() : $this->name;
      // la base
      $this->base = $public? "public":"storage";
      // the url
      $this->url = $path?? "videos";
      // format
      $this->format = $video->getClientOriginalExtension();
      // guardamos la original
      $video->move($this->getPathToSave()."/$this->name/","original.$this->format");
      //
      $this->save();
      //
      jobCompressVideo::dispatch($this);
    }

    //
    public function getPath()
    {
      if($this->base == "public")
        return url($this->url);
      else
        return url("$this->base/"."app/public/".$this->url);
    }

    // get the path to save
    public function getPathToSave()
    {
      // if the image is in the storage we have to put it in the storage/app/public folder
      if ($this->base == "storage") {
        return storage_path("app/public/$this->url");
      } else {
        return $this->getPath();
      }
    }

    // random token for the image
    public function generateToken()
    {
        return md5(uniqid(rand(), true));
    }

    public function removeVideo()
    {
      // quitamos el video
      $base = $this->getPathToSave()."/".$this->name;
      $this->rmdir_recursive($base);
    }

    public function removeOriginalVideo()
    {
      $base = $this->getPathToSave()."/".$this->name."/original.".$this->format;
      unlink($base);
    }

    public function exportAndDelete()
    {
      if($this->exported) {
        return false;
      }
      // base url
      $base = "public/videos/".$this->name."/original".".".$this->format;
      // abrimos archivo
      $video = FFMpeg::open($base);
      // Dimension
      $dimensions = $video->getVideoStream()->getDimensions();
      // cogemos height and with
      $height = $dimensions->getHeight();
      //
      $width = $dimensions->getWidth();
      //

      // echo "w: $width , h: $height";
      // ahora debemos mirar, si la altura es mas grande que anchura entonces el rescale será a 480, 720
      // $newHeight = 480;
      // $newWidth = 720;
      // if($height>$width) {
      //   $newWidth = 480;
      //   $newHeight = 720;
      //   echo "es vertical";
      // } else {
      //   echo "no es vertical";
      // }//libx264
      try {

        $lowBitrateFormat = (new \FFMpeg\Format\Video\X264('copy'))->setKiloBitrate(2000);
        $video->export()
        ->inFormat($lowBitrateFormat)
        ->save("public/videos/".$this->name."/exported".".".$this->format);

      } catch (\Exception $e) {
        $this->failed = true;
        $this->save();
        return false;
      }

      $this->exported = true;
      $this->save();
      try {
        $this->removeOriginalVideo();
      } catch (\Exception $e) {

      }
      return true;



    }

    // elimina los archivos de una dirección
    public function rmdir_recursive($dir) {
        foreach(scandir($dir) as $file) {
            if ('.' === $file || '..' === $file) continue;
            if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
            else unlink("$dir/$file");
        }
        rmdir($dir);
    }
}
