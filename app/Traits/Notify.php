<?php





namespace App\Traits;

use App\Events\NotificationEvent;
use App\Jobs\jobNotify;
use GuzzleHttp\Client;
use App\Notification;


/**
 * Trait Object for sending notifications
 *
 */
trait Notify
{

  // it sends the notification via socket or via push
  public function send($data,$other = null)
  {
    // $this->sendPush($data);
    $this->sendSocket($data);
    $noti = new Notification([
      "user_id" => $this->id?? 0,
      "from_id" => $this->id?? 0,
      "title"   => $data['title'],
      "body"    => $data['body'],
      "type"    => $data['type'],
      "data"    => $data['data']
    ]);
    $noti->save();
    return true;
  }

  // send the notification in the socket
  public function sendSocket($noti)
  {
    try {
      // data and user
      broadcast(new NotificationEvent($noti,$this));
    } catch (\Exception $e) {
      return false;
    }
  }

  public function sendPush($data)
  {
    if($this->device_token !== null) {
      jobNotify::dispatch($this,$data);
    }
  }





}
