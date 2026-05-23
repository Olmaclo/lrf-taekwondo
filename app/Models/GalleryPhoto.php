<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryPhoto extends Model
{
    protected $fillable = [
        'path', 'original_name', 'caption', 'event_id', 'uploaded_by', 'size',
    ];

    protected $appends = ['url', 'size_formatted'];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // ── Accessors ──────────────────────────────────────────────────────────────

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    public function getSizeFormattedAttribute(): string
    {
        if ($this->size < 1024) {
            return $this->size . ' B';
        }
        if ($this->size < 1_048_576) {
            return round($this->size / 1024, 1) . ' Ko';
        }
        return round($this->size / 1_048_576, 1) . ' Mo';
    }
}
