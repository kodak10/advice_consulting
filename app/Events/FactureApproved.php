<?php
// app/Events/FactureApproved.php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FactureApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $facture;

    public function __construct($facture)
    {
        $this->facture = $facture;
    }

    public function broadcastOn()
    {
        return new Channel('facture.' . $this->facture->user_id); // Diffusion sur un canal spécifique
    }
}
