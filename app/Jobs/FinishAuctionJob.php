<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Auction\AuctionServiceProvider;

use App\Auction;

class FinishAuctionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $auction;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($auction_id)
    {
      $this->auction = Auction::find($auction_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        if($this->auction) {
          $provider = new AuctionServiceProvider();
          $provider->finishAuction($this->auction);
        }


    }
}
