<?php

namespace App\Mail;

use App\Models\BookQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookAvailableMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public BookQueue $bookQueue
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Reserved Book is Available',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.book-available',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}