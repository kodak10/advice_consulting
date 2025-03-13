<?php

namespace App\Notifications;

use App\Models\Devis;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class DevisRefusedNotification extends Notification
{
    public $devis;

    public function __construct(Devis $devis)
    {
        $this->devis = $devis;
    }

    /**
     * Get the notification's delivery channels.
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
            'title' => 'Proforma',
            'icon' => 'ti-circle-minus',
            'message' => 'La Proforma ' . $this->devis->reference . ' a été refusé.',
            'devis_id' => $this->devis->id,
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
            'title' => 'Proforma',
            'message' => 'La Proforma ' . $this->devis->reference . ' a été refusé.',
            'devis_id' => $this->devis->id,
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
            'title' => 'Proforma',
            'message' => 'La Proforma ' . $this->devis->reference . ' a été refusé.',
            'devis_id' => $this->devis->id,
        ];
    }
}
