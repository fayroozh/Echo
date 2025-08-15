<?php

namespace App\Events;

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTypingStatus implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $conversation;
    public $isTyping;

    public function __construct(User $user, Conversation $conversation, bool $isTyping)
    {
        $this->user = $user;
        $this->conversation = $conversation;
        $this->isTyping = $isTyping;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('conversation.' . $this->conversation->id);
    }

    public function broadcastAs()
    {
        return 'user-typing';
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->user->id,
            'conversation_id' => $this->conversation->id,
            'is_typing' => $this->isTyping
        ];
    }
}