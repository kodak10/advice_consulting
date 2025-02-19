<?php

namespace App\Events;

use App\Models\Devis;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DevisCreated
{
    use InteractsWithSockets, SerializesModels;

    public $devis;

    public function __construct(Devis $devis)
    {
        $this->devis = $devis;
    }

    public function broadcastOn()
    {
        return new Channel('devis.notifications');
    }

    public function broadcastAs()
    {
        return 'devis.created';
    }
}
