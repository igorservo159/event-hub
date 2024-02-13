<?php

namespace App\Listeners;

use App\Events\RegistrationCreated;
use App\Notifications\NewRegistration;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRegistrationCreatedNotification implements ShouldQueue
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
    public function handle(RegistrationCreated $event): void
    {
        $registration = $event->registration;

        $user = $registration->user;
        
        if($user !== $registration->event->owner){
            $user->notify(new NewRegistration($registration));
        }
    }
}
