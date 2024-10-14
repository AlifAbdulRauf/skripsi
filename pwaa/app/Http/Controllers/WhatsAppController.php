<?php

namespace App\Http\Controllers;

use Twilio\Rest\Client;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class WhatsAppController extends Controller
{
    public function sendWhatsAppMessage(Request $request)
    {
        $twilioSid = "AC11c4912c70a2167cd628621406eefef3";
        $twilioToken = "ffef5f0a4fee7c1bcbb7cd8696d2265d";
        $twilioWhatsAppNumber = "whatsapp:+14155238886";
        $recipientNumber = $request->input('phone'); // Ambil nomor dari request
        $message = $request->input('message'); // Ambil pesan dari request

        $twilio = new Client($twilioSid, $twilioToken);

        try {
            $twilio->messages->create(
                "whatsapp:$recipientNumber",
                [
                    "from" => $twilioWhatsAppNumber,
                    "body" => $message,
                ]
            );

            Alert::success('Success', 'Pesan berhasil dikirim');
            return redirect()->route('reservasi.index');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
