<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Facture;

class FactureApprovedNotification extends Notification
{
    protected $facture;

    public function __construct(Facture $facture)
    {
        $this->facture = $facture;
    }

    // Sélectionner les canaux par lesquels la notification sera envoyée
    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Notification via base de données et broadcast
    }

    // Notification à enregistrer dans la base de données
    public function toDatabase($notifiable)
    {
        

        return [
            'icon'  => 'ti-clipboard-data',
            'title' => 'Facture',
            'facture_id' => $this->facture->id,
            'message' => 'La facture pour la proforma ' . $this->facture->devis->reference . ' a été validée.',
            'url' => url('/factures/' . $this->facture->id)
        ];
    }

    // Diffusion de la notification (broadcast)
    public function toBroadcast($notifiable)
    {
        return [
            'icon'  => 'ti-clipboard-data',
            'title' => 'Facture',

            'facture_id' => $this->facture->id,
            'message' => 'La facture pour le devis ' . $this->facture->devis->reference . ' a été validée.',
        ];
    }
}
