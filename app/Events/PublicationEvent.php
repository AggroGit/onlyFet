<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Publication;

class PublicationEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    //
    public $publication;
    public $app_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Publication $publication)
    {
        //
        $this->publication = $publication;
        //
        $this->app_id = env('APP_ID', '97');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
      $id = $this->publication->id;
      return new PresenceChannel("$this->app_id.Publication.$id");
    }
}
