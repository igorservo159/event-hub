<?php

namespace App\Models;

use App\Events\RefundCreated;
use App\Events\RefundStatusChanged;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'reason',
        'explanation',
        'decisao',
        'payment_id',
        'user_id',
    ];

    protected $dispatchesEvents = [
        'created' => RefundCreated::class,
    ];

    public function payment(): BelongsTo {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($refund) {
            $originalStatus = $refund->getOriginal('decisao');
            $newStatus = $refund->getAttribute('decisao');

            // Se o status foi alterado
            if ($originalStatus !== $newStatus && ($newStatus === 'negada' || $newStatus === 'aprovada')) {
                event(new RefundStatusChanged($refund, $newStatus));
            }
        });
    }

}
