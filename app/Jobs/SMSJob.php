<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\Model\SendMessageRequest;

class SMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phoneNo;
    protected $message;

    public function __construct($phoneNo, $message)
    {
        $this->phoneNo = $phoneNo;
        $this->message = $message;
    }


    public function handle()
    {
        // Configure client
        $config = Configuration::getDefaultConfiguration();
        $config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTY0NTUzMDA5MywiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjkzMTI1LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.fYMteqJ81ns-Q5VveOyzNdZTGh-lkGsxTOqnQVUVKoA');
        $apiClient = new ApiClient($config);
        $messageClient = new MessageApi($apiClient);

        $sendMessageRequest1 = new SendMessageRequest([
            'phoneNumber' => $this->phoneNo,
            'message' =>  $this->message,
            'deviceId' => 127827
        ]);

        $messageClient->sendMessages([
            $sendMessageRequest1,
        ]);
    }
}
