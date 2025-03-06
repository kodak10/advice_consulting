<?php

namespace App\Mail;

use App\Models\Devis;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DevisRefusedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $devis;

    /**
     * Créer une nouvelle instance de message.
     *
     * @param  \App\Models\Devis  $devis
     * @return void
     */
    public function __construct(Devis $devis)
    {
        $this->devis = $devis;
    }

    /**
     * Construire le message à envoyer.
     *
     * @return \Illuminate\Contracts\Mail\Mailable
     */
    public function build()
    {
        return $this->subject('Devis Refusé')
                    ->view('emails.devis_refused'); // Assure-toi que la vue existe
    }
}
