<?php

namespace App\Mail;

use App\Models\Candidature;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CandidatureRecue extends Mailable
{
    use Queueable, SerializesModels;

    public $candidature;

    public function __construct(Candidature $candidature)
    {
        $this->candidature = $candidature;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Confirmation de votre candidature - NEW BRAIN FACTORY',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.candidature_recue',
        );
    }
}