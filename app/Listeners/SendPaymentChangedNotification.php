<?php

namespace App\Listeners;

use App\Events\PaymentStatusChanged;
use App\Notifications\PaymentResult;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPaymentChangedNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentStatusChanged $event): void
    {
        $payment = $event->payment;

        $user = $payment->user;

        $user->notify(new PaymentResult($event->string));
    }
}
