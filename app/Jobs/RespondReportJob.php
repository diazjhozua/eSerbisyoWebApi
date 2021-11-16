<?php

namespace App\Jobs;

use App\Mail\RespondReportMail;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class RespondReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $report;
    protected $subject;

    public function __construct(Report $report, $subject)
    {
        $this->report = $report;
        $this->subject = $subject;
    }

    public function handle()
    {
        Mail::to($this->report->user->email)
            ->send(new RespondReportMail($this->report, $this->subject));
    }
}
