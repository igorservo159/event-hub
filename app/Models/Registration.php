<?php

namespace App\Models;

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
    
    public function event(): BelongsTo {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function payments(): HasMany {
        return $this->hasMany(Payment::class, 'registration_id', 'id');
    }
}
