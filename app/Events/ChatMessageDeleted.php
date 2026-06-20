<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @param int[] $messageIds */
    public function __construct(public int $liveSessionId, public array $messageIds) {}

    public function broadcastOn(): array
    {
        return [new Channel('live.' . $this->liveSessionId)];
    }

    public function broadcastAs(): string
    {
        return 'chat.deleted';
    }

    public function broadcastWith(): array
    {
        return ['ids' => $this->messageIds];
    }
}
