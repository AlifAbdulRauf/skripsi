<?php

namespace App\Services;

use Twilio\Rest\Client;

class WhatsAppService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
    }

    public function sendWhatsAppMessage($recipientNumber, $message)
    {
        $twilioWhatsAppNumber = "whatsapp:+14155238886";
        if (substr($recipientNumber, 0, 2) == '08') {
            $recipientNumber = '628' . substr($recipientNumber, 2);
        }

        try {
            $this->twilio->messages->create(
                "whatsapp:$recipientNumber",
                [
                    "from" => $twilioWhatsAppNumber,
                    "body" => $message,
                ]
            );
        } catch (\Exception $e) {
            throw new \Exception("Error sending WhatsApp message: " . $e->getMessage());
        }
    }
}
