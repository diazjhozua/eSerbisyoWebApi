<?php

namespace App\Jobs;

use App\Helper\Helper;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSingleNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $deviceId;
    protected $userId;
    protected $subject;
    protected $message;
    protected $modelId;
    protected $modelName;

    public function __construct($deviceId, $userId, $subject, $message, $modelId, $modelName)
    {
        $this->deviceId = $deviceId;
        $this->userId = $userId;
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
        $devices = [$this->deviceId];

        $data=array(
            'title'=> $this->subject,
            'body'=> $this->message,
        );

        Helper::instance()->sendFCMNotification($devices,$data);

        Notification::create([
            'user_id' => $this->userId,
            'message' => $this->message,
            'notifiable_id' => $this->modelId,
            'notifiable_type' => $this->modelName,
        ]);
    }
}
