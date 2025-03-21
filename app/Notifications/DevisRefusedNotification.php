<?php

namespace App\Notifications;

use App\Models\Devis;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class DevisRefusedNotification extends Notification
{
    public $devis;

    // Le constructeur prend une facture comme paramètre
    public function __construct(Devis $devis)
    {
        $this->devis = $devis;
    }

    /**
     * Obtenir les canaux de notification (base de données et broadcast).
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Notification via la base de données et broadcast
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
            'title' => 'Proforma Refusée',  // Titre de la notification
            'icon' => 'ti-circle-minus',   // Icône pour l'interface
            'message' => 'La Proforma ' . $this->devis->num_proforma . ' a été refusée.',  // Message de la notification
            'devis_id' => $this->devis->id,  // ID de la facture pour plus de détails
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
            'title' => 'Proforma Refusée',
            'message' => 'La Proforma ' . $this->devis->num_proforma . ' a été refusée.',
            'devis_id' => $this->devis->id,  // ID de la facture
        ]);
    }

    /**
     * Représentation sous forme de tableau (utile pour les notifications stockées).
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Proforma Refusée',
            'message' => 'La Proforma ' . $this->devis->num_proforma . ' a été refusée.',
            'devis_id' => $this->devis->id,  // ID de la facture pour les liens ou détails supplémentaires
        ];
    }
}
