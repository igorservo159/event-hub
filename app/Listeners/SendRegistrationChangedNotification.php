<?php

namespace App\Listeners;

use App\Events\RegistrationStatusChanged;
use App\Notifications\RegistrationResult;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRegistrationChangedNotification implements ShouldQueue
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
    public function handle(RegistrationStatusChanged $event): void
    {
        $registration = $event->registration;

        $user = $registration->user;

        $user->notify(new RegistrationResult($event->string));
    }
}
