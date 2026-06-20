<?php

namespace App\Events;

use App\Models\Poll;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PollUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Poll $poll) {}

    public function broadcastOn(): array
    {
        return [new Channel('live.' . $this->poll->live_session_id)];
    }

    public function broadcastAs(): string
    {
        return 'poll.updated';
    }

    public function broadcastWith(): array
    {
        return $this->poll->broadcastPayload();
    }
}
