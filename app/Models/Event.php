<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'type',
        'start_date', 'end_date', 'location', 'cover_image',
        'registration_fee', 'status', 'created_by', 'registration_deadline',
    ];

    protected function casts(): array
    {
        return [
            'start_date'            => 'date',
            'end_date'              => 'date',
            'registration_deadline' => 'datetime',
            'registration_fee'      => 'float',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $event) {
            if (empty($event->slug)) {
                $base = Str::slug($event->name) . '-' . now()->year;
                $slug = $base;
                $i    = 2;
                while (static::withTrashed()->where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $event->slug = $slug;
            }
        });
    }

    // ── Relationships ──────────────────────────────────────────────────────────

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function athletes(): HasMany
    {
        return $this->hasMany(Athlete::class);
    }

    public function draws(): HasMany
    {
        return $this->hasMany(Draw::class);
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_image ? asset('storage/' . $this->cover_image) : null;
    }

    // ── Lifecycle states ───────────────────────────────────────────────────────

    /** Statuts considérés comme « clôturés » : aucune écriture métier autorisée. */
    public const LOCKED_STATUSES = ['finished', 'cancelled'];

    // ── Registration guard ─────────────────────────────────────────────────────

    public function isRegistrationOpen(): bool
    {
        return $this->status === 'open'
            && ($this->registration_deadline === null || $this->registration_deadline->isFuture());
    }

    /**
     * Un événement verrouillé (terminé ou annulé) n'accepte plus de modification
     * métier (paiements, pesées, tirages…) — sauf override d'un technicien.
     */
    public function isLocked(): bool
    {
        return in_array($this->status, self::LOCKED_STATUSES, true);
    }

    // ── Scopes ─────────────────────────────────────────────────────────────────

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now())->orderBy('start_date');
    }

    /** Événements encore vivants (ni terminés ni annulés). */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', self::LOCKED_STATUSES);
    }

    /** Événements archivés (terminés ou annulés). */
    public function scopeArchived($query)
    {
        return $query->whereIn('status', self::LOCKED_STATUSES);
    }

    // ── Accessors ──────────────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'upcoming'  => 'À venir',
            'open'      => 'Inscriptions ouvertes',
            'closed'    => 'Inscriptions fermées',
            'ongoing'   => 'En cours',
            'finished'  => 'Terminé',
            'cancelled' => 'Annulé',
            default     => ucfirst((string) $this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'upcoming'  => 'badge-blue',
            'open'      => 'badge-green',
            'closed'    => 'badge-gold',
            'ongoing'   => 'badge-orange',
            'finished'  => 'badge-surface',
            'cancelled' => 'badge-red',
            default     => 'badge-surface',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'kyorugi' => 'Kyorugi',
            'poomsae' => 'Poomsae',
            'mixed'   => 'Mixte',
            'other'   => 'Autre',
            default   => ucfirst((string) $this->type),
        };
    }

    // ── Stats ──────────────────────────────────────────────────────────────────

    public function getAthleteStatsAttribute(): array
    {
        $q = $this->athletes();
        return [
            'total'     => $q->count(),
            'validated' => $q->clone()->where('registration_status', 'validated')->count(),
            'pending'   => $q->clone()->where('registration_status', 'pending')->count(),
            'rejected'  => $q->clone()->where('registration_status', 'rejected')->count(),
            'paid'      => $q->clone()->whereIn('payment_status', ['paid', 'validated'])->count(),
        ];
    }

    public function getCategoriesAttribute(): array
    {
        return $this->athletes()
            ->where('registration_status', 'validated')
            ->selectRaw('age_category, gender, weight_category, COUNT(*) as count')
            ->groupBy('age_category', 'gender', 'weight_category')
            ->orderBy('age_category')
            ->get()
            ->toArray();
    }
}
