<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Event;
use App\Models\Refund;
use App\Models\Registration;
use App\Policies\EventPolicy;
use App\Policies\RefundPolicy;
use App\Policies\RegistrationPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Event::class => EventPolicy::class,
        Registration::class => RegistrationPolicy::class,
        Refund::class => RefundPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('list-registers', function ($user, $event) {
            return $user->id === $event->owner->id;
        });

        Gate::define('create-payment', function ($user, $registration) {
            return $user->id === $registration->user_id;
        });

        Gate::define('approve-payment', function ($user, $registration) {
            return $user->id === $registration->event->owner->id;
        });

        Gate::define('store-refund', function ($user, $registration) {
            return $user->id === $registration->user->id;
        });

        Gate::define('list-refund', function ($user, $event) {
            return $user->id === $event->owner->id;
        });

        Gate::define('askForRefund-refund', function ($user, $registration) {
            return $user->id === $registration->user->id;
        });
    }
}
