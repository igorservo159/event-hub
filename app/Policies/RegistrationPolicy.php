<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;

class RegistrationPolicy
{
    public function cancel(User $user, Registration $registration)
    {
        return $user->isAdmin() || 
                ($user->id === $registration->user_id &&
                ($registration->status == 'pendente'
                || $registration->status == 'staff' 
                || $registration->status == 'processando pagamento'
                || $registration->status == 'pago'));
    }

    public function listRegisters(User $user, Event $event)
    {
        return $user->id === $event->owner->id;
    }
}
