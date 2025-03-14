<?php

namespace App\Notifications;

use App\Models\Facture;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class FactureCreatedNotification extends Notification
{
    public $facture;

    // Le constructeur prend une facture comme paramètre
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
            'title' => 'Facture Créée',  // Titre de la notification
            'icon' => 'ti-check',   
            'message' => 'Le devis N°' . $this->facture->devis->num_proforma . ' a été créée.',
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
            'title' => 'Facture Créée',
            'message' => 'Le devis N°' . $this->facture->devis->num_proforma . ' a été créée.',
            'facture_id' => $this->facture->id,  
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
            'title' => 'Facture Créée',
            'message' => 'Le devis N°' . $this->facture->devis->num_proforma . ' a été créée.',
            'facture_id' => $this->facture->id,  // ID de la facture pour les liens ou détails supplémentaires
        ];
    }
}
