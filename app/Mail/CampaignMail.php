<?php

namespace App\Mail;

use App\Models\EmailCampaign;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public EmailCampaign $campaign,
        public string $recipientName = ''
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->campaign->subject);
    }

    public function content(): Content
    {
        // Replace {name} placeholder with the actual recipient name
        $body = str_replace('{name}', e($this->recipientName ?: 'Cliente'), $this->campaign->body);

        return new Content(
            view: 'emails.campaign',
            with: [
                'campaign'      => $this->campaign,
                'recipientName' => $this->recipientName,
                'body'          => $body,
            ]
        );
    }
}
