<?php

namespace App\Providers;

use App\Events\AccountChanged;
use App\Events\EventCompleted;
use App\Events\PaymentCreated;
use App\Events\PaymentStatusChanged;
use App\Events\RefundCreated;
use App\Events\RefundStatusChanged;
use App\Events\RegistrationCreated;
use App\Events\RegistrationStatusChanged;
use App\Listeners\SendAccountChangedNotification;
use App\Listeners\SendFullEventNotification;
use App\Listeners\SendPaymentChangedNotification;
use App\Listeners\SendPaymentCreatedNotification;
use App\Listeners\SendRefundChangedNotification;
use App\Listeners\SendRefundCreatedNotification;
use App\Listeners\SendRegistrationChangedNotification;
use App\Listeners\SendRegistrationCreatedNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        RegistrationCreated::class => [
            SendRegistrationCreatedNotification::class,
        ],

        PaymentCreated::class => [
            SendPaymentCreatedNotification::class,
        ],

        RefundCreated::class => [
            SendRefundCreatedNotification::class,
        ],

        RegistrationStatusChanged::class => [
            SendRegistrationChangedNotification::class,
        ],

        PaymentStatusChanged::class => [
            SendPaymentChangedNotification::class,
        ],

        RefundStatusChanged::class => [
            SendRefundChangedNotification::class,
        ],

        AccountChanged::class => [
            SendAccountChangedNotification::class,
        ],

        EventCompleted::class => [
            SendFullEventNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
