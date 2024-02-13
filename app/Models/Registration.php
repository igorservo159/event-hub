<?php

namespace App\Models;

use App\Events\RegistrationCreated;
use App\Events\RegistrationStatusChanged;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'event_id',
    ];

    protected $dispatchesEvents = [
        'created' => RegistrationCreated::class,
    ];
    
    public function event(): BelongsTo {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function payments(): HasMany {
        return $this->hasMany(Payment::class, 'registration_id', 'id');
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($registration) {
            $originalStatus = $registration->getOriginal('status');
            $newStatus = $registration->getAttribute('status');

            // Se o status foi alterado
            if ($originalStatus !== $newStatus && ($newStatus === 'cancelada' || $newStatus === 'pago')
                && !($newStatus === 'pago' && $originalStatus === 'esperando por reembolso')) {
                event(new RegistrationStatusChanged($registration, $newStatus));
            }
        });
    }

}
