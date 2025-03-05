<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FactureMail extends Mailable
{
    use Queueable, SerializesModels;


    public $facture;
    public $pdfPath;
    public $creator;
    public $creatorName;
    public $clientEmail;

    /**
     * Create a new message instance.
     */
    public function __construct($facture, $pdfPath, $creator, $creatorName, $clientEmail)
    {
        $this->facture = $facture;
        $this->pdfPath = $pdfPath;
        $this->creator = $creator;
        $this->creatorName = $creatorName;
        $this->clientEmail = $clientEmail;  // Ajoutez cette ligne


        
    }

    public function build()
    {
    
        return $this->from($this->creator, $this->creatorName) // Expéditeur dynamique
                    ->to($this->clientEmail) // Destinataire obligatoire
                    ->subject("Votre facture #" . $this->facture->id)
                    ->view('administration.vues.facturesEmail')
                    ->attach($this->pdfPath, [
                        'as' => 'facture.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
    

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Facture',
        );
    }

    /**
     * Get the message content definition.
     */
    

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    
}
