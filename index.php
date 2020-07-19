<?php
/**
 * Use for return easy answer.
 */

require_once('./vendor/autoload.php');

use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

//Token
$channel_token = 'LCbmoMjbF2nRv/Otz/dWhlTDAFIEDWQhmQrcAwn2xz9wEm8/OZcznhNKgVt6pHAkixKM/w4CbrVXb+AVb+uUbQ4sEhsCliL9/TaY57smH118ZKmo+OiV/biDXkJzzeFq1zGtFu12OQslMNbkSeEYywdB04t89/1O/w1cDnyilFU=';
$channel_secret = 'b3ae34bda8a0a53b84a4ab8d11dc3106';

// Database connection 
$host = 'ec2-3-216-129-140.compute-1.amazonaws.com';
$dbname = 'dbvmu2coiaa2rd';
$user = 'mpndlrkjmdpngd';
$pass = 'e13e293509484313814ada8dfdd44aff4db023173cfde906d7d01e22c49242de';
$connection = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass); 

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
			
			//รับข้อความ
			// แยกตัวแปร
			$message = $events['events'][0]['message']['text'];
			$text_ex = explode(':', $message); //เอาข้อความมาแยก : ได้เป็น Array
            //$respMessage = $text_ex[0];
				if($text_ex[0] == "subcon")
				{ 
				//$respMessage = $text_ex[1];
				// Query
                            $sql = sprintf("SELECT * FROM subcon WHERE access_no='$text_ex[1]'");
                            $result = $connection->query($sql);
							if($result !== false && $result->rowCount() >0) 
								{
									$row = $result ->fetch(PDO::FETCH_ASSOC);
									//$row = pg_fetch_array($result);
										//$amount = $result->rowCount();
										//$respMessage =("%s (%s)\n", $row[0], $row[1]);
										$respMessage="Circuit: ".$row['access_no']."\nผรม: ".$row['subcontractor']."\nจังหวัด: ".$row['province']."\nวันที่ติดตั้ง: ".$row['installed_date']."\nวันที่หมดประกัน: ".$row['waranty_expire_date']."\n==========\nTeam Code: ".$row['team_code']."\nFOA8 User: ".$row['wfm8_user']."\nStaff Name: ".$row['staff_name']."\nTel: ".$row['phone']."\n
										"
										;
								} 
							else
								{
										$respMessage ="ไม่มีข้อมูลของ Circuit: ".$text_ex[1];
								}
				}
				else 
				{
					
                $respMessage = "ไม่มีคำสั่งนี้ \n\r 
				ตัวอย่างคำสั่งที่ใช้ได้ \n\r
				subcon:{circuit|96XXXXXXX}";
					
				}

            $httpClient = new CurlHTTPClient($channel_token);
            $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));

            $textMessageBuilder = new TextMessageBuilder($respMessage);
            $response = $bot->replyMessage($replyToken, $textMessageBuilder);

		}
	}
}

echo "OK";
