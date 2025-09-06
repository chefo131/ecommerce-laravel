<?php

namespace App\Mail;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReviewApproved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * La instancia de la reseña.
     * Hacemos la propiedad pública para que sea accesible desde la vista.
     */
    public Review $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Tu reseña ha sido aprobada!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.review-approved',
        );
    }
}
