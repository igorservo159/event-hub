<?php

namespace App\Listeners;

use App\Events\RefundCreated;
use App\Notifications\NewRefund;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRefundCreatedNotification implements ShouldQueue
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
    public function handle(RefundCreated $event): void
    {
        $refund = $event->refund;

        $user = $refund->user;

        $user->notify(new NewRefund($refund));
    }
}
