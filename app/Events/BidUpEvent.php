<?php

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use App\Auction;

class BidUpEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $auction;
    public $app_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Auction $auction)
    {
      $this->auction = $auction;
      $this->app_id = env('APP_ID', '97');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
      $id = $this->auction->id;
      return new PresenceChannel("$this->app_id.Auction.$id");
    }
}
