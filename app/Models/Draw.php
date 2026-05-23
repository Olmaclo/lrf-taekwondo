<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Draw extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'category', 'age_category', 'gender', 'weight_category',
        'total_athletes', 'use_pools', 'matches', 'pools',
        'generated_by', 'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'use_pools'    => 'boolean',
            'matches'      => 'array',
            'pools'        => 'array',
            'generated_at' => 'datetime',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────────────────

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    // ── Accessors ──────────────────────────────────────────────────────────────

    public function getCategoryLabelAttribute(): string
    {
        return $this->category;
    }

    public function getMatchCountAttribute(): int
    {
        if ($this->use_pools && $this->pools) {
            return collect($this->pools)->sum(fn ($p) => count($p['matches'] ?? []));
        }
        return count($this->matches ?? []);
    }

    public function getWinnerAttribute(): ?array
    {
        if (!$this->matches) return null;
        $final = collect($this->matches)->where('round', 'final')->first();
        return $final ? ($final['winner'] ?? null) : null;
    }
}
