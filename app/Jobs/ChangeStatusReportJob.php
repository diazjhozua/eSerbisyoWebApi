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
use Log;

use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\Model\SendMessageRequest;

class ChangeStatusReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emailTo;
    protected $id;
    protected $reportName;
    protected $status;
    protected $adminMessage;
    protected $subject;
    protected $phoneNo;

    public function __construct($emailTo, $id, $reportName, $status, $adminMessage, $subject, $phoneNo)
    {
        $this->emailTo = $emailTo;
        $this->id = $id;
        $this->reportName = $reportName;
        $this->status = $status;
        $this->adminMessage = $adminMessage;
        $this->subject = $subject;
        $this->phoneNo = $phoneNo;
    }

    public function handle()
    {
        // Configure client
        $config = Configuration::getDefaultConfiguration();
        $config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTY0NTUzMDA5MywiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjkzMTI1LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.fYMteqJ81ns-Q5VveOyzNdZTGh-lkGsxTOqnQVUVKoA');
        $apiClient = new ApiClient($config);
        $messageClient = new MessageApi($apiClient);

        if ($this->status == 'Pending') {
            $mess1 = 'Your submitted '.$this->reportName.' #'.$this->id.' was changed its status by "'.$this->status.'" by the administrator. There might be that there is a wrong input or wrong credentials in submitting the report. Please edit the specified report.';
        } elseif ($this->status == 'Approved') {
            $mess1 = 'Your submitted '.$this->reportName.' #'.$this->id.' was changed its status by "'.$this->status.'" by the administrator. User who uses the e-serbisyo can now see the specified report. Thank you for using the application. Avoid editing the specified report, otherwise it will marked again as pending.';
        } elseif ($this->status == 'Resolved') {
            $mess1 = 'Your submitted '.$this->reportName.' #'.$this->id.' was changed its status by "'.$this->status.'" by the administrator. by the administrator. Please go to the barangay office as soon as possible.';
        } elseif ($this->status == 'Denied') {
            $mess1 = 'Your submitted '.$this->reportName.' #'.$this->id.' was changed its status by "'.$this->status.'" by the administrator. There might be that there is a wrong input or wrong credentials in submitting the report. Please edit again the specified report (It will automatically marked as pending).';
        }

        $mess2 = PHP_EOL.PHP_EOL.'Admin Message: '.$this->adminMessage.PHP_EOL.PHP_EOL.'-Barangay Cupang';

        $message = $mess1.$mess2;
        // Sending a SMS Message
        $sendMessageRequest1 = new SendMessageRequest([
            'phoneNumber' => $this->phoneNo,
            'message' => $message,
            'deviceId' => 127363
        ]);

        $messageClient->sendMessages([
            $sendMessageRequest1,
        ]);

        Mail::to($this->emailTo)
            ->send(new ChangeStatusReportMail($this->id, $this->reportName, $this->status, $this->adminMessage, $this->subject));
    }
}
