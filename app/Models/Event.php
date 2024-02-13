<?php

namespace App\Models;

use App\Events\EventCompleted;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'data',
        'location',
        'capacity',
        'price',
        'owner_id',
    ];

    protected $casts = [
        'data' => 'datetime',
    ];

    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function registrations(): HasMany {
        return $this->hasMany(Registration::class, 'event_id', 'id');
    }

    public function hasAvailableSlots(): bool
    {
        return $this->registrations()->where('status', '!=', 'cancelada')->count() < $this->capacity;
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($event) {
            if ($event->registrations()->where('status', '!=', 'cancelada')
            ->count() >= $event->capacity) {
                event(new EventCompleted($event));
            }
        });
    }
}
