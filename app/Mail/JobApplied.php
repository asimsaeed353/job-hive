<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class JobApplied extends Mailable
{
    use Queueable, SerializesModels;

    // pass in the job and the application to show the details in the email body
    public $application;
    public $job;

    /**
     * Create a new message instance.
     */
    public function __construct($application, $job)
    {
        $this->application = $application;
        $this->job = $job;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Job Application',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.job-applied',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        if($this->application->resume_paht){
            $attachments = Attachment::fromPath(storage_path('app/public' . $this->application->resume_paht))
                                    ->as($this->application->resume_paht)
                                    ->withMime('application/pd');
        }

        return $attachments;
    }
}
