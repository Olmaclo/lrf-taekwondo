<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'title', 'youtube_video_id', 'status',
        'description', 'started_at', 'ended_at', 'peak_viewers', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'started_at'   => 'datetime',
            'ended_at'     => 'datetime',
            'peak_viewers' => 'integer',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────────────────

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Scopes ─────────────────────────────────────────────────────────────────

    public function scopeLive($query)
    {
        return $query->where('status', 'live');
    }

    public function scopeVisible($query)
    {
        // Un live consultable publiquement : en cours ou terminé (replay)
        return $query->whereIn('status', ['live', 'ended']);
    }

    // ── State ──────────────────────────────────────────────────────────────────

    public function isLive(): bool
    {
        return $this->status === 'live';
    }

    public function isEnded(): bool
    {
        return $this->status === 'ended';
    }

    // ── Accessors ──────────────────────────────────────────────────────────────

    public function getEmbedUrlAttribute(): string
    {
        // autoplay + mute permet la lecture auto sur la plupart des navigateurs
        return "https://www.youtube.com/embed/{$this->youtube_video_id}?autoplay=1&rel=0";
    }

    public function getWatchUrlAttribute(): string
    {
        return "https://www.youtube.com/watch?v={$this->youtube_video_id}";
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'scheduled' => 'Programmé',
            'live'      => 'En direct',
            'ended'     => 'Terminé',
            default     => ucfirst((string) $this->status),
        };
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    /**
     * Extrait l'identifiant vidéo YouTube depuis un ID brut ou n'importe quelle
     * forme d'URL : watch?v=, youtu.be/, /live/, /embed/, /shorts/.
     */
    public static function extractYoutubeId(string $input): ?string
    {
        $input = trim($input);

        // Déjà un ID brut (11 caractères)
        if (preg_match('/^[A-Za-z0-9_-]{11}$/', $input)) {
            return $input;
        }

        if (preg_match(
            '~(?:youtube\.com/(?:watch\?(?:.*&)?v=|live/|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{11})~',
            $input,
            $m
        )) {
            return $m[1];
        }

        return null;
    }
}
