<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Empêche toute écriture métier sur un événement clôturé (terminé/annulé).
     * Les techniciens conservent la main pour corriger un historique (override).
     */
    protected function ensureEventWritable(?Event $event): void
    {
        if ($event && $event->isLocked() && ! Auth::user()?->isTechnical()) {
            abort(422, "Action impossible : l'événement « {$event->name} » est {$event->status_label}. "
                . "Seul un membre de l'équipe technique peut le rouvrir pour le modifier.");
        }
    }
}
