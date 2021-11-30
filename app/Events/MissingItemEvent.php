<?php

namespace App\Events;

use App\Http\Resources\MissingItemResource;
use App\Models\MissingItem;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MissingItemEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $missingItem;

    public function __construct(MissingItem $missingItem)
    {
        $this->missingItem = New MissingItemResource($missingItem);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('missingItem-channel');
    }

    public function broadcastAs()
    {
        return 'missingItem-channel';
    }
}
