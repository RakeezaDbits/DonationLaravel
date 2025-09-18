<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DonationRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name, $amount, $reason;

    public function __construct($name, $amount, $reason = null)
    {
        $this->name = $name;
        $this->amount = $amount;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Donation Rejected')
            ->view('emails.donations.rejected')
            ->with([
                'subject' => 'Donation Rejected',
                'name' => $this->name,
                'amount' => '$' . number_format($this->amount, 2),
                'reason' => $this->reason,
            ]);
    }
}
