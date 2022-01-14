<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;


    public $order;
    public $subject;
    public $changeValue;

    public function __construct(Order $order, $subject, $changeValue)
    {
        $this->order = $order;
        $this->subject = $subject;
        $this->changeValue = $changeValue;
    }

    public function build()
    {
        return $this->markdown('emails.changeOrderStatus', [
            'order' => $this->order,
            'subject' => $this->subject,
            'changeValue' => $this->changeValue,
        ])->subject($this->subject);
    }
}
