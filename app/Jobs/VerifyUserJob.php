<?php

namespace App\Jobs;

use App\Mail\VerifyUserMail;
use App\Models\Notification;
use App\Models\User;
use Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class VerifyUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $subject;
    protected $request;

    public function __construct(User $user, $subject, $request)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->request = $request;
    }

    public function handle()
    {

        $devices = [$this->user->device_id];

        $data=array(
            'title'=> "Verification alert",
            'body'=> $this->subject == 'Verified Account' ? 'Your account has been verified. Please relogin the application' : 'Your account verification request has been denied.'
        );

        Helper::instance()->sendFCMNotification($devices,$data);

        Notification::create([
            'user_id' => $this->user->id,
            'message' => $this->subject == 'Verified Account' ? 'Your account has been verified. Please relogin the application' : 'Your account verification request has been denied.',
            'notifiable_id' => $this->user->id,
            'notifiable_type' => "App\Models\VerificationRequest",
        ]);

        Mail::to($this->user->email)
            ->send(new VerifyUserMail($this->subject, $this->request));
    }
}
