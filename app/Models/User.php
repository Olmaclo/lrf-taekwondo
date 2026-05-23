<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password',
        'phone', 'club', 'birth_date', 'birth_place', 'gender', 'country',
        'license_number', 'federal_code',
        'is_validated', 'account_status', 'avatar', 'bio',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'birth_date'        => 'date',
            'is_validated'      => 'boolean',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────────────────

    public function athletes(): HasMany
    {
        return $this->hasMany(Athlete::class, 'coach_id');
    }

    public function createdEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function validatedAthletes(): HasMany
    {
        return $this->hasMany(Athlete::class, 'validated_by');
    }

    public function generatedDraws(): HasMany
    {
        return $this->hasMany(Draw::class, 'generated_by');
    }

    // ── Password reset ────────────────────────────────────────────────────────

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $initials = collect(explode(' ', $this->name))
            ->map(fn ($p) => strtoupper(substr($p, 0, 1)))
            ->take(2)->implode('');
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128">'
             . '<rect width="128" height="128" rx="64" fill="#f59e0b"/>'
             . '<text x="64" y="64" dominant-baseline="central" text-anchor="middle" '
             . 'font-family="Arial,sans-serif" font-size="52" font-weight="bold" fill="#0f172a">'
             . htmlspecialchars($initials, ENT_XML1)
             . '</text></svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isTechnical(): bool
    {
        return $this->hasAnyRole(['admin', 'technical']);
    }

    public function isCoach(): bool
    {
        return $this->hasRole('coach');
    }

    public function isFinancial(): bool
    {
        return $this->hasAnyRole(['admin', 'financial']);
    }
}
