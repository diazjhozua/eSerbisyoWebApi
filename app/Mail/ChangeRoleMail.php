<?php

namespace App\Mail;

use App\Models\UserRole;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChangeRoleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $userRole;

    public function __construct($subject, UserRole $userRole)
    {
        $this->subject = $subject;
        $this->userRole = $userRole;
    }

    public function build()
    {
        return $this->markdown('emails.changeRole', [
            'userRole' => $this->userRole,
            'subject' => $this->subject,
        ])->subject($this->subject);

    }
}
