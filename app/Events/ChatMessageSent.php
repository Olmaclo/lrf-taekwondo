<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ChatMessage $message) {}

    /**
     * Canal public : tout le monde peut écouter le chat d'un live donné.
     */
    public function broadcastOn(): array
    {
        return [new Channel('live.' . $this->message->live_session_id)];
    }

    public function broadcastAs(): string
    {
        return 'chat.message';
    }

    public function broadcastWith(): array
    {
        return [
            'id'      => $this->message->id,
            'pseudo'  => $this->message->pseudo,
            'message' => $this->message->message,
            'time'    => $this->message->created_at->format('H:i'),
        ];
    }
}
