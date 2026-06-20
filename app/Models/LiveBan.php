<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveBan extends Model
{
    protected $fillable = [
        'live_session_id', 'pseudo', 'ip_hash', 'banned_by',
    ];

    public function liveSession(): BelongsTo
    {
        return $this->belongsTo(LiveSession::class);
    }

    /**
     * Un visiteur est-il banni de ce live (par pseudo OU empreinte IP) ?
     */
    public static function isBanned(int $liveSessionId, ?string $pseudo, ?string $ipHash): bool
    {
        return static::where('live_session_id', $liveSessionId)
            ->where(function ($q) use ($pseudo, $ipHash) {
                if ($pseudo) {
                    $q->where('pseudo', $pseudo);
                }
                if ($ipHash) {
                    $q->orWhere('ip_hash', $ipHash);
                }
            })
            ->exists();
    }
}
