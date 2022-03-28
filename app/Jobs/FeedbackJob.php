<?php

namespace App\Jobs;

use App\Mail\FeedbackMail;
use App\Models\Feedback;
use App\Models\Notification;
use Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;


class FeedbackJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $feedback;

    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }

    public function handle()
    {

        $devices = [$this->feedback->user->device_id];

        $data=array(
            'title'=> "Feedback alert",
            'body'=> "Your feedback #".$this->feedback->id." has been responded by our administrator."
        );

        Helper::instance()->sendFCMNotification($devices,$data);

        Notification::create([
            'user_id' => $this->feedback->user->id,
            'message' => "Your feedback #".$this->feedback->id." has been responded by our administrator.",
            'notifiable_id' => $this->feedback->id,
            'notifiable_type' => "App\Models\Feedback",
        ]);

        Mail::to($this->feedback->user->email)
            ->send(new FeedbackMail($this->feedback));
    }
}
