<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function create(User $user)
    {
        return $user->isOrganizador();
    }

    public function myEvents(User $user)
    {
        return $this->create($user);
    }

    public function update(User $user, Event $event)
    {
        return $user->isOrganizador() && $user->id === $event->owner->id;
    }

    public function delete(User $user, Event $event)
    {
        return $this->update($user, $event);
    }

}
