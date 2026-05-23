<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialLog extends Model
{
    protected $fillable = [
        'athlete_id', 'event_id', 'user_id', 'action',
        'previous_status', 'new_status', 'amount', 'notes', 'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount'   => 'decimal:2',
            'metadata' => 'array',
        ];
    }

    public function athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'payment_recorded'     => 'Paiement enregistré',
            'payment_edited'       => 'Paiement modifié',
            'temp_validated'       => 'Validation temporaire',
            'definitive_validated' => 'Validation définitive',
            'receipt_generated'    => 'Reçu généré',
            'status_changed'       => 'Statut modifié',
            default                => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }
}
