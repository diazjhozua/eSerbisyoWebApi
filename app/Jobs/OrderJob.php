<?php

namespace App\Jobs;

use App\Mail\OrderMail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class OrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    protected $subject;
    protected $message;

    public function __construct(Order $order, $subject, $message)
    {
        $this->order = $order;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function handle()
    {
        Mail::to($this->order->email)
            ->send(new OrderMail($this->order, $this->subject, $this->message));
    }
}
