<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WaveLog extends Model
{
    use HasFactory;

    public function scopeFilter($query, array $filters)
    {
        if ($filters['username'] ?? false) {
            $user = User::where('username', request('username'))->firstOrFail();
            $query->where("user_id", $user->id);
        }
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $with = ['user'];
}
