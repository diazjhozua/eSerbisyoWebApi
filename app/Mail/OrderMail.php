<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $subject;
    public $message;

    public function __construct(Order $order, $subject, $message)
    {
        $this->order = $order;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     *
     */
    public function build()
    {
        return $this->markdown('emails.order', [
            'order' => $this->order,
            'subject' => $this->subject,
            'message' => $this->message,
        ])->subject($this->subject);
    }
}
