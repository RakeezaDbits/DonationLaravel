<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DonationApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name, $amount;

    public function __construct($name, $amount)
    {
        $this->name = $name;
        $this->amount = $amount;
    }

    public function build()
    {
        return $this->subject('Donation Approved')
            ->view('emails.donations.approved')
            ->with([
                'subject' => 'Donation Approved',
                'name' => $this->name,
                'amount' => '$' . number_format($this->amount, 2),
            ]);
    }
}

