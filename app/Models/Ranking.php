<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ranking extends Model
{
    protected $fillable = [
        'athlete_id', 'event_id', 'season', 'category',
        'position', 'points', 'wins', 'losses',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    public static function pointsForPosition(int $position): int
    {
        return match ($position) {
            1 => 10,
            2 => 7,
            3 => 5,
            4 => 3,
            default => 1,
        };
    }

    public function getMedalAttribute(): ?string
    {
        return match ($this->position) {
            1 => 'Or',
            2 => 'Argent',
            3 => 'Bronze',
            default => null,
        };
    }

    public function getMedalColorAttribute(): string
    {
        return match ($this->position) {
            1 => 'badge-gold',
            2 => 'badge-surface',
            3 => 'badge-orange',
            default => 'badge-surface',
        };
    }
}
