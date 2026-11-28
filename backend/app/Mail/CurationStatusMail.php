<?php

namespace App\Mail;

use App\Models\Karya;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CurationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Karya $karya,
        public readonly string $statusBaru,
        public readonly ?string $catatan = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[FUTURiSE] Pembaruan Status Kurasi: ' . $this->karya->judul,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.curation-status',
        );
    }
}
