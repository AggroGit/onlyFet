<?php

namespace App\Jobs;


use App\Services\Propina\PropinaServiceProvider;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use App\PayOut;

class sendMoney implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PayOut $payOut)
    {
        //
        $payOut->refresh();
        $this->payment = $payOut;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      PropinaServiceProvider::payToUser($this->payment);
    }
}
