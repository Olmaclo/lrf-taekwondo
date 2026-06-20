<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReactionSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public int $liveSessionId, public string $emoji) {}

    public function broadcastOn(): array
    {
        return [new Channel('live.' . $this->liveSessionId)];
    }

    public function broadcastAs(): string
    {
        return 'reaction';
    }

    public function broadcastWith(): array
    {
        return ['emoji' => $this->emoji];
    }
}
