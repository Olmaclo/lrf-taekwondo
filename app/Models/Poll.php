<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = [
        'live_session_id', 'question', 'options', 'status', 'created_by',
    ];

    protected function casts(): array
    {
        return ['options' => 'array'];
    }

    public function liveSession(): BelongsTo
    {
        return $this->belongsTo(LiveSession::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /** Compteur de votes par option, dans l'ordre des options. */
    public function results(): array
    {
        $counts = $this->votes()
            ->selectRaw('option_index, COUNT(*) as c')
            ->groupBy('option_index')
            ->pluck('c', 'option_index');

        return collect($this->options)->values()->map(fn ($opt, $i) => [
            'label' => $opt,
            'count' => (int) ($counts[$i] ?? 0),
        ])->all();
    }

    /** Données diffusées aux spectateurs (Echo). */
    public function broadcastPayload(): array
    {
        $results = $this->results();

        return [
            'id'       => $this->id,
            'question' => $this->question,
            'options'  => $this->options,
            'results'  => $results,
            'total'    => array_sum(array_column($results, 'count')),
            'status'   => $this->status,
        ];
    }
}
