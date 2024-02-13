<?php

namespace App\Listeners;

use App\Events\RefundStatusChanged;
use App\Notifications\RefundResult;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRefundChangedNotification implements ShouldQueue
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
    public function handle(RefundStatusChanged $event): void
    {
        $refund = $event->refund;

        $user = $refund->user;

        $user->notify(new RefundResult($event->string));
    }
}
