<?php

namespace App\Listeners;

use App\Events\PaymentCreated;
use App\Notifications\NewPayment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPaymentCreatedNotification implements ShouldQueue
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
    public function handle(PaymentCreated $event): void
    {
        $payment = $event->payment;

        $user = $payment->user;

        $user->notify(new NewPayment($payment));
    }
}
