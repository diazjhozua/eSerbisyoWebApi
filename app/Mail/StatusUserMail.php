<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StatusUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $request;

    public function __construct($subject, $request)
    {
        $this->subject = $subject;
        $this->request = $request;
    }

    public function build()
    {
        return $this->markdown('emails.statusUser', [
            'request' => $this->request,
        ])->subject($this->subject);

    }
}
