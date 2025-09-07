<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class UserNotificationMail extends Mailable
{
    public string $subjectText;
    public string $bodyHtml;

    public function __construct(string $subject, string $body)
    {
        $this->subjectText = $subject;
        $this->bodyHtml = $body;
    }

    public function build()
    {
        return $this->subject($this->subjectText)
                    ->html($this->bodyHtml);
    }
}
