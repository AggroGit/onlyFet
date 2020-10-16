<?php


namespace App\Traits;

use Pusher\Pusher;
use App\User;

/**
 * Trait for object that can be putted in a socket
 *
 */
trait Sockeable
{

  public function saludar()
  {
    return "Hi ".$this->getName();
  }


  // if we dont specify the protected channel name in model, we take the name
  // of the model
  public function getChannelName($presence=true)
  {
    if($presence)
      return 'presence-'.env('APP_ID',97).'.'.$this->getName().'.'.$this->id;
    else
    return env('APP_ID',97).'.'.$this->getName().'.'.$this->id;
    // "presence-"$this->getName().".".$this->id;
  }
  // get the name of a class
  public function getName() {
    $path = explode('\\', get_class($this));
    return array_pop($path);
  }

  // return the pusher connection
  public function connection()
  {
    return new Pusher(
              env('PUSHER_APP_KEY'),
              env('PUSHER_APP_SECRET'),
              env('PUSHER_APP_ID'),
              array(
                  'cluster' => env('PUSHER_APP_CLUSTER'),
                  'host'=> env('SOCKETS_IP','127.0.0.1'),
                  'port'=>'6001')
              );
  }

  public function getChannelCon()
  {
    return $this->connection()->get_channel_info($this->getChannelName());
  }

  public function isConected()
  {
    return $this->getChannelCon()? true:false;
  }

  // return ALL the channels of this model
  public function getAll()
  {
    return $results = $this->connection()
                           ->get_channels([
                             'filter_by_prefix' => 'presence-App.'.$this->getName().'.'
                           ]);
  }

  // if a user is coected to this channel
  public function isUserConnected(User $user)
  {
    $array = $this->connection()->get_users_info($this->getChannelName());
    $array = json_decode(json_encode($array), true);
    if($array['users']?? false) {
      foreach ($array['users'] as $userAr) {
        if($userAr['id'] == $user->id) {
          return true;
        }
      }
    }
    return false;
  }


















}
