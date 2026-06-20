<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'live_session_id', 'pseudo', 'message', 'ip_hash', 'is_deleted',
    ];

    protected function casts(): array
    {
        return ['is_deleted' => 'boolean'];
    }

    public function liveSession(): BelongsTo
    {
        return $this->belongsTo(LiveSession::class);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_deleted', false);
    }
}
