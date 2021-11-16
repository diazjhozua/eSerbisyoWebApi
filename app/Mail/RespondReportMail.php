<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RespondReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $report;
    public $subject;

    public function __construct(Report $report, $subject)
    {
        $this->report = $report;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->markdown('emails.respondReport', [
            'report' => $this->report,
            'subject' => $this->subject,
        ])->subject($this->subject);
    }
}
