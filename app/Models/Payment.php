<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'method',
        'status',
    ];

    public function registration(): BelongsTo {
        return $this->belongsTo(Registration::class, 'registration_id', 'id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
