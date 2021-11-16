<?php

namespace App\Events;

use App\Http\Resources\ReportResource;
use App\Models\Report;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReportEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $report;

    public function __construct(Report $report)
    {
        $this->report = New ReportResource($report);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('report-channel');
    }

    public function broadcastAs()
    {
        return 'report-channel';
    }

}
