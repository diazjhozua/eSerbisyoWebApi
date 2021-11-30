<?php

namespace App\Events;

use App\Http\Resources\MissingPersonResource;
use App\Models\MissingPerson;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MissingPersonEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $missingPerson;

    public function __construct(MissingPerson $missingPerson)
    {
        $this->missingPerson = New MissingPersonResource($missingPerson);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('missingPerson-channel');
    }

    public function broadcastAs()
    {
        return 'missingPerson-channel';
    }
}
