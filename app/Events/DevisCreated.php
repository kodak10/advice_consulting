<?php

namespace App\Events;

use App\Models\Devis;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DevisCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $devis;

    /**
     * Crée une nouvelle instance de l'événement.
     *
     * @param  Devis  $devis
     * @return void
     */
    public function __construct(Devis $devis)
    {
        $this->devis = $devis;
        Log::info('DevisCreated event dispatched with Devis ID: ' . $devis->id);  // Log pour s'assurer que l'événement est dispatché
    }

    /**
     * Le canal sur lequel diffuser l'événement.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]
     */
    public function broadcastOn()
    {
        Log::info("Broadcasting on channel: user." . $this->devis->user_id);  // Log pour vérifier que le bon canal est utilisé

        return new PrivateChannel('user.' . $this->devis->user_id);  // Canal privé pour l'utilisateur spécifique
    }

    /**
     * Le nom de l'événement pour le broadcasting.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'devis.created';  // Nom de l'événement
    }
}
