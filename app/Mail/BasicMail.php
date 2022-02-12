<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BasicMail extends Mailable
{
    use Queueable, SerializesModels;

    public $feedback;

    public function __construct($feedback)
    {
        $this->feedback = $feedback;
    }


    public function build()
    {
        return $this->markdown('emails.basicMail', [
            'message' => $this->message,
        ])->subject($this->subject);
    }
}
