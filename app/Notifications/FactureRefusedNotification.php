<?php

namespace App\Notifications;

use App\Models\Facture;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class FactureRefusedNotification extends Notification
{
    use Queueable;

    protected $facture;

    // Le constructeur prend la facture comme paramètre
    public function __construct(Facture $facture)
    {
        $this->facture = $facture;
    }

    /**
     * Récupérer les canaux de notification que nous souhaitons utiliser.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Préparer le message de notification pour la base de données.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\DatabaseMessage
     */
    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'title' => 'Proforma Refusée',  // Titre de la notification
            'icon' => 'ti-circle-minus',   // Icône pour l'interface
            'facture_id' => $this->facture->id,
            'status' => $this->facture->status,
            'message' => "La facture N°" . $this->facture->numero .  " a été refusée.",
        ]);
    }
    /**
     * Préparer le message de notification pour le broadcast.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'facture_id' => $this->facture->id,
            'status' => $this->facture->status,
            'message' => "La facture N°" . $this->facture->numero . " a été refusée.",
        ]);
    }
}
