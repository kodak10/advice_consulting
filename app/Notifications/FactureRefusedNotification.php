<?php

namespace App\Notifications;

use App\Models\Facture;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class FactureRefusedNotification extends Notification
{
    use Queueable;

    protected $facture;

    // Le constructeur prend une facture en paramètre
    public function __construct(Facture $facture)
    {
        $this->facture = $facture;
    }

    /**
     * Obtenir les canaux de notification (base de données et broadcast).
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Notification via la base de données et broadcasting
    }

    /**
     * Enregistrer la notification dans la base de données.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Facture Refusée',  
            'icon' => 'ti-alert-circle',   
            'message' => 'La facture N°' . $this->facture->numero . ' a été refusée.',
            'facture_id' => $this->facture->id,  
        ];
    }

    /**
     * Diffuser la notification via le broadcast.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Facture Refusée',
            'message' => 'La facture N°' . $this->facture->numero . ' a été refusée.',
            'facture_id' => $this->facture->id,  
        ]);
    }

    /**
     * Représentation sous forme de tableau.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Facture Refusée',
            'message' => 'La facture N°' . $this->facture->id . ' a été refusée.',
            'facture_id' => $this->facture->id,  
        ];
    }
}
