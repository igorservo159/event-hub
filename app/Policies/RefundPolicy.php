<?php

namespace App\Policies;

use App\Models\Refund;
use App\Models\User;

class RefundPolicy
{
    public function ApproveOrDenyRefund(User $user, Refund $refund)
    {
        return $user->isAdmin() || $user->id === $refund->payment->registration->event->owner->id;
    }

}