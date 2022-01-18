<?php

namespace App\Events;

use App\Http\Resources\UserVerificationResource;
use App\Models\UserVerification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserVerificationEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $userVerification;

    public function __construct(UserVerification $userVerification)
    {
        $this->userVerification = New UserVerificationResource($userVerification);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('userVerification-channel');
    }


    public function broadcastAs()
    {
        return 'userVerification-channel';
    }
}
