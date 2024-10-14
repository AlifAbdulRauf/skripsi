<?php
    // Update the path below to your autoload.php,
    // see https://getcomposer.org/doc/01-basic-usage.md
    require_once '/path/to/vendor/autoload.php';
    use Twilio\Rest\Client;

    $sid    = "AC11c4912c70a2167cd628621406eefef3";
    $token  = "ffef5f0a4fee7c1bcbb7cd8696d2265d";
    $twilio = new Client($sid, $token);

    $message = $twilio->messages
      ->create("whatsapp:+6282288051901", // to
        array(
          "from" => "whatsapp:+14155238886",
          "body" => "Your appointment is coming up on July 21 at 3PM"
        )
      );

print($message->sid);



// <?php
//     // Update the path below to your autoload.php,
//     // see https://getcomposer.org/doc/01-basic-usage.md
//     require_once '/path/to/vendor/autoload.php';
//     use Twilio\Rest\Client;

//     $sid    = "AC11c4912c70a2167cd628621406eefef3";
//     $token  = "ffef5f0a4fee7c1bcbb7cd8696d2265d";
//     $twilio = new Client($sid, $token);

//     $message = $twilio->messages
//       ->create("whatsapp:+6282288051901", // to
//         array(
//           "from" => "whatsapp:+14155238886",
//           "body" => oke baik
//         )
//       );

// print($message->sid);


// 201 - CREATED - The request was successful. We created a new resource and the response body contains the representation.
// {
//   "account_sid": "AC11c4912c70a2167cd628621406eefef3",
//   "api_version": "2010-04-01",
//   "body": "Your appointment is coming up on July 21 at 3PM",
//   "date_created": "Mon, 22 Jul 2024 14:10:10 +0000",
//   "date_sent": null,
//   "date_updated": "Mon, 22 Jul 2024 14:10:10 +0000",
//   "direction": "outbound-api",
//   "error_code": null,
//   "error_message": null,
//   "from": "whatsapp:+14155238886",
//   "messaging_service_sid": null,
//   "num_media": "0",
//   "num_segments": "1",
//   "price": null,
//   "price_unit": null,
//   "sid": "SM619edc1ba4a74c940a0b7c3cbd3350cf",
//   "status": "queued",
//   "subresource_uris": {
//     "media": "/2010-04-01/Accounts/AC11c4912c70a2167cd628621406eefef3/Messages/SM619edc1ba4a74c940a0b7c3cbd3350cf/Media.json"
//   },
//   "to": "whatsapp:+6282288051901",
//   "uri": "/2010-04-01/Accounts/AC11c4912c70a2167cd628621406eefef3/Messages/SM619edc1ba4a74c940a0b7c3cbd3350cf.json"
// }

// 201 - CREATED - The request was successful. We created a new resource and the response body contains the representation.
// {
//   "account_sid": "AC11c4912c70a2167cd628621406eefef3",
//   "api_version": "2010-04-01",
//   "body": "oke baik",
//   "date_created": "Mon, 22 Jul 2024 14:15:44 +0000",
//   "date_sent": null,
//   "date_updated": "Mon, 22 Jul 2024 14:15:44 +0000",
//   "direction": "outbound-api",
//   "error_code": null,
//   "error_message": null,
//   "from": "whatsapp:+14155238886",
//   "messaging_service_sid": null,
//   "num_media": "0",
//   "num_segments": "1",
//   "price": null,
//   "price_unit": null,
//   "sid": "SMaefc4145cfdf05203afe602856a22ef5",
//   "status": "queued",
//   "subresource_uris": {
//     "media": "/2010-04-01/Accounts/AC11c4912c70a2167cd628621406eefef3/Messages/SMaefc4145cfdf05203afe602856a22ef5/Media.json"
//   },
//   "to": "whatsapp:+6282288051901",
//   "uri": "/2010-04-01/Accounts/AC11c4912c70a2167cd628621406eefef3/Messages/SMaefc4145cfdf05203afe602856a22ef5.json"
// }