<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function registrations(): HasMany {
        return $this->hasMany(Registration::class, 'user_id', 'id');
    }

    public function payments(): HasMany {
        return $this->hasMany(Payment::class, 'user_id', 'id');
    }

    public function createdEvents(): HasMany {
        return $this->hasMany(Event::class, 'owner_id', 'id');
    }

    public function isEnrolled(Event $event): bool 
    {
        return $this->registrations()
            ->where('event_id', $event->id)
            ->exists();
    }

    public function isOrganizador(): bool {
        return $this->type === 'organizador';
    }

    public function isInscrito(): bool {
        return $this->type === 'inscrito';
    }

    public function isAdmin(): bool {
        return $this->type === 'administrador';
    }
}
