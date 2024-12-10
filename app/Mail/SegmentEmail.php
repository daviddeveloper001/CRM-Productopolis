<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;

class SegmentEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    public $attachment;

    public function __construct($mailData, $attachment = null)
    {
        $this->mailData = $mailData;
        //dd($this->mailData['message']);
        $this->attachment = $attachment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notification: ' . ($this->mailData['name'] ?? 'Sin Nombre'),
            from: new Address(
                env('MAIL_FROM_ADDRESS', 'no-reply@myplataform.com'),
                env('MAIL_FROM_NAME', 'Administración')
            )
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'email.segment-email', 
            with: [ 
                'name' => $this->mailData['name'] ?? 'Cliente',
                'message' => $this->mailData['message'] ?? '',
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Adjuntar archivo si está presente
        if ($this->attachment) {
            return [
                Attachment::fromPath(storage_path('app/public/' . $this->attachment))
                    ->as(basename($this->attachment))
                    ->withMime('application/pdf'), // Cambia según el tipo de archivo
            ];
        }

        return [];
    }
}
