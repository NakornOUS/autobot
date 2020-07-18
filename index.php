<?php
/**
 * Use for return easy answer.
 */

require_once('./vendor/autoload.php');

use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

$channel_token = 'LCbmoMjbF2nRv/Otz/dWhlTDAFIEDWQhmQrcAwn2xz9wEm8/OZcznhNKgVt6pHAkixKM/w4CbrVXb+AVb+uUbQ4sEhsCliL9/TaY57smH118ZKmo+OiV/biDXkJzzeFq1zGtFu12OQslMNbkSeEYywdB04t89/1O/w1cDnyilFU=';
$channel_secret = 'b3ae34bda8a0a53b84a4ab8d11dc3106';

// Get message from Line API
$content = file_get_contents('php://input');
$events = json_decode($content, true);

if (!is_null($events['events'])) {

	// Loop through each event
	foreach ($events['events'] as $event) {
    
        // Line API send a lot of event type, we interested in message only.
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {

            // Get replyToken
            $replyToken = $event['replyToken'];
			// แยกตัวแปร
			//รับข้อความจากผู้ใช้
			$message = $events['events'][0]['message']['text'];
			//$text = $event['message']['text'];
			$text_ex = explode(':', $message); //เอาข้อความมาแยก : ได้เป็น Array
            $respMessage = $text_ex[0];

            $httpClient = new CurlHTTPClient($channel_token);
            $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));

            $textMessageBuilder = new TextMessageBuilder($respMessage);
            $response = $bot->replyMessage($replyToken, $textMessageBuilder);

		}
	}
}

echo "OK";
