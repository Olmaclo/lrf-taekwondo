<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

class AutoFinishEvents extends Command
{
    protected $signature = 'events:auto-finish';

    protected $description = "Passe à 'finished' les événements dont la date de fin est dépassée";

    public function handle(): int
    {
        // Aujourd'hui à 00h00 : un événement n'est « terminé » qu'à partir du lendemain
        // de sa date de fin (end_date), ou de sa date de début si aucune end_date.
        $cutoff = now()->startOfDay();

        $events = Event::active()
            ->where(function ($q) use ($cutoff) {
                $q->where(function ($q2) use ($cutoff) {
                    $q2->whereNotNull('end_date')->where('end_date', '<', $cutoff);
                })->orWhere(function ($q2) use ($cutoff) {
                    $q2->whereNull('end_date')->where('start_date', '<', $cutoff);
                });
            })
            ->get();

        if ($events->isEmpty()) {
            $this->info('Aucun événement à clôturer.');
            return self::SUCCESS;
        }

        foreach ($events as $event) {
            $event->update(['status' => 'finished']);
            $this->line("  • « {$event->name} » → terminé (fin : "
                . ($event->end_date ?? $event->start_date)->format('d/m/Y') . ')');
        }

        $this->info("{$events->count()} événement(s) clôturé(s) automatiquement.");

        return self::SUCCESS;
    }
}
