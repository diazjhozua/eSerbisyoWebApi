<?php

namespace App\Jobs;

use App\Mail\BasicMail;
use App\Models\Notification;
use App\Models\User;
use Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $users;
    protected $subject;
    protected $message;
    protected $modelId;
    protected $modelName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($users, $subject, $message, $modelId, $modelName)
    {
        $this->users = $users;
        $this->subject = $subject;
        $this->message = $message;
        $this->modelId = $modelId;
        $this->modelName = $modelName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        activity()->disableLogging();

        $devices = $this->users->pluck('device_id');

        $data=array(
            'title'=> $this->subject,
            'body'=> $this->message
        );

        Helper::instance()->sendFCMNotification($devices,$data);

        foreach ($this->users as $user) {
            Mail::to($user->email)
            ->send(new BasicMail($this->subject, $this->message));

            Notification::create([
                'user_id' => $user->id,
                'message' => $this->message,
                'notifiable_id' => $this->modelId,
                'notifiable_type' => $this->modelName,
            ]);
        }
    }
}
