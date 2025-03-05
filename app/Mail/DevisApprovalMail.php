<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class DevisApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $devis;
    public $pdfPath;
    public $userName;
    /**
     * Create a new message instance.
     */
    public function __construct($devis, $pdfPath, $userName)
    {
        $this->devis = $devis;
        $this->pdfPath = $pdfPath;
        $this->userName = $userName;
    }

    public function build()
    {
        if (!Auth::check()) {
            throw new \Exception("Aucun utilisateur authentifié.");
        }
    
        $user = Auth::user();
    
        return $this->subject('Devis Approuvé')
                    ->view('administration.vues.devisEmail')
                    ->with([
                        'devisNumber' => $this->devis->num_proforma,
                        'clientName' => $this->devis->client->nom,
                        'userName' => $this->userName,
                    ])
                    ->attach($this->pdfPath, [
                        'as' => 'devis_' . $this->devis->num_proforma . '.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->from($user->email, $user->name)
                    ->to($this->devis->client->email);
    }

    

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Proforma Advice Consulting',
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
