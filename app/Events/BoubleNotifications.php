<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

use Illuminate\Queue\SerializesModels;

class BoubleNotifications implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    protected $user,$app_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userIn=null)
    {
        //
        $this->user = $userIn?? auth()->user();
        $this->app_id = env('APP_ID', '97');
    }

    public function broadcastWith()
    {
      return [
        "chat_notis" => $this->user->chat_notis,
        "other_notis" => $this->user->other_notis
      ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
      $id = $this->user->id?? 0;
      return new PresenceChannel("$this->app_id.User.$id");
    }
}
