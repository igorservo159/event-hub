<?php

namespace App\Listeners;

use App\Events\AccountChanged;
use App\Notifications\AccountRequestResult;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAccountChangedNotification implements ShouldQueue
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
    public function handle(AccountChanged $event): void
    {
        $permissionRequest = $event->permissionRequest;

        $user = $permissionRequest->user;

        $user->notify(new AccountRequestResult($permissionRequest, $event->string));
    }
}
