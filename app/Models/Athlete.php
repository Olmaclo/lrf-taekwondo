<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Athlete extends Model
{
    use HasFactory, SoftDeletes;

    // Only fields safe for general CRUD (coaches/technical input)
    protected $fillable = [
        'first_name', 'last_name', 'birth_date', 'birth_place', 'gender', 'nationality', 'photo',
        'weight',
        'age_category', 'weight_category', 'club', 'license_number',
        'current_grade', 'target_grade', 'years_practice', 'last_grade_date', 'master_name',
        'event_id', 'coach_id',
        'created_by', 'last_modified_by',
    ];

    // Sensitive state fields — only updated via forceFill() in authorized controllers
    protected $guarded = [
        'registration_status', 'validated_by', 'validated_at', 'rejection_reason',
        'payment_status', 'payment_amount', 'payment_method', 'transaction_ref',
        'receipt_number', 'payment_date',
        'temp_validation_deadline', 'temp_validation_notes', 'temp_validated_by', 'temp_validated_at',
    ];

    protected $appends = [
        'full_name', 'age', 'category_label',
        'registration_status_label', 'payment_status_label',
        'photo_url', 'coach_name',
    ];

    protected function casts(): array
    {
        return [
            'birth_date'               => 'date',
            'last_grade_date'          => 'date',
            'validated_at'             => 'datetime',
            'payment_date'             => 'datetime',
            'temp_validation_deadline' => 'datetime',
            'temp_validated_at'        => 'datetime',
            'payment_amount'           => 'float',
            'weight'                   => 'float',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────────────────

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Scopes ─────────────────────────────────────────────────────────────────

    public function scopeValidated($query)
    {
        return $query->where('registration_status', 'validated');
    }

    public function scopePending($query)
    {
        return $query->where('registration_status', 'pending');
    }

    public function scopeForEvent($query, int $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeForCategory($query, string $age, string $gender, string $weight)
    {
        return $query->where('age_category', $age)
                     ->where('gender', $gender)
                     ->where('weight_category', $weight);
    }

    // ── Accessors ──────────────────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date?->age;
    }

    public function getCategoryLabelAttribute(): string
    {
        $label = static::genderLabel($this->gender, $this->age_category ?? '');
        return "{$this->age_category} {$label} {$this->weight_category}";
    }

    public function getGenderLabelAttribute(): string
    {
        return static::genderLabel($this->gender, $this->age_category ?? '');
    }

    public static function genderLabel(string $gender, string $ageCategory): string
    {
        $isSenior = strtolower(trim($ageCategory)) === 'senior';
        if ($gender === 'M') {
            return $isSenior ? 'Homme' : 'Garçon';
        }
        return $isSenior ? 'Dame' : 'Fille';
    }

    public function getCoachNameAttribute(): ?string
    {
        return $this->relationLoaded('coach') ? $this->coach?->name : null;
    }

    public function getRegistrationStatusLabelAttribute(): string
    {
        return match ($this->registration_status) {
            'pending'   => 'En attente',
            'validated' => 'Validé',
            'rejected'  => 'Rejeté',
            default     => ucfirst((string) $this->registration_status),
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'unpaid'         => 'Non payé',
            'temp_validated' => 'Pré-validé',
            'paid'           => 'Payé',
            'validated'      => 'Validé',
            default          => ucfirst((string) $this->payment_status),
        };
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        $initials = strtoupper(
            substr($this->first_name ?? '', 0, 1) . substr($this->last_name ?? '', 0, 1)
        );
        $bg = $this->gender === 'M' ? '3b82f6' : 'ec4899';
        return "https://ui-avatars.com/api/?name={$initials}&background={$bg}&color=fff&bold=true&size=128";
    }

    // ── Static helpers ─────────────────────────────────────────────────────────

    public static function generateReceiptNumber(): string
    {
        $last  = static::withTrashed()->whereNotNull('receipt_number')->orderByDesc('id')->value('receipt_number');
        $seq   = $last ? ((int) substr($last, -5)) + 1 : 1;
        return 'RCT-' . date('Y') . '-' . str_pad($seq, 5, '0', STR_PAD_LEFT);
    }
}
