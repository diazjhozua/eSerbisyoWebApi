<?php

namespace App\Jobs;

use App\Mail\OrderStatusMail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class OrderStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    protected $subject;
    protected $changeValue;

    public function __construct(Order $order, $subject, $changeValue)
    {
        $this->order = $order;
        $this->subject = $subject;
        $this->changeValue = $changeValue;
    }

    public function handle()
    {
        Mail::to($this->order->email)
            ->send(new OrderStatusMail($this->order, $this->subject, $this->changeValue));
    }
}
