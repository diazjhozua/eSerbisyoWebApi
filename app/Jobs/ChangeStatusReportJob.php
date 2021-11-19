<?php

namespace App\Jobs;

use App\Mail\ChangeStatusReportMail;
use App\Models\MissingPerson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ChangeStatusReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emailTo;
    protected $id;
    protected $reportName;
    protected $status;
    protected $adminMessage;
    protected $subject;

    public function __construct($emailTo, $id, $reportName, $status, $adminMessage, $subject)
    {
        $this->emailTo = $emailTo;
        $this->id = $id;
        $this->reportName = $reportName;
        $this->status = $status;
        $this->adminMessage = $adminMessage;
        $this->subject = $subject;
    }

    public function handle()
    {
        Mail::to($this->emailTo)
            ->send(new ChangeStatusReportMail($this->id, $this->reportName, $this->status, $this->adminMessage, $this->subject));
    }
}
