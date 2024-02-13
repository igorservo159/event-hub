<?php

namespace App\Models;

use App\Events\AccountChanged;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'requested_type', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($permissionRequest) {
            $originalStatus = $permissionRequest->getOriginal('status');
            $newStatus = $permissionRequest->getAttribute('status');

            // Se o status foi alterado
            if ($originalStatus !== $newStatus && ($newStatus === 'negado' || $newStatus === 'aprovado')) {
                event(new AccountChanged($permissionRequest, $newStatus));
            }
        });
    }
}

