<?php

namespace App\Listeners;

use App\Events\EventCompleted;
use App\Notifications\FullEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendFullEventNotification implements ShouldQueue
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
    public function handle(EventCompleted $event_): void
    {
        $event = $event_->event;

        $user = $event->owner;

        $user->notify(new FullEvent($event));
    }
}
