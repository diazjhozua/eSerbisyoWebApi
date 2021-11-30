<?php

namespace App\Mail;

use App\Models\MissingPerson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChangeStatusReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $id;
    public $reportName;
    public $status;
    public $adminMessage;
    public $subject;

    public function __construct($id, $reportName, $status, $adminMessage, $subject)
    {
        $this->id = $id;
        $this->reportName = $reportName;
        $this->status = $status;
        $this->adminMessage = $adminMessage;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->markdown('emails.changeStatusReport', [
            'id' => $this->id,
            'reportName' => $this->reportName,
            'status' => $this->status,
            'adminMessage' => $this->adminMessage,
            'subject' => $this->subject,
        ])->subject($this->subject);
    }
}
