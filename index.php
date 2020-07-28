<?php
	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);
	require_once('./vendor/autoload.php');
	//require __DIR__ . '/vendor/autoload.php';
	

	//#-------------------------[Token]-------------------------#
$channelAccessToken = 'LCbmoMjbF2nRv/Otz/dWhlTDAFIEDWQhmQrcAwn2xz9wEm8/OZcznhNKgVt6pHAkixKM/w4CbrVXb+AVb+uUbQ4sEhsCliL9/TaY57smH118ZKmo+OiV/biDXkJzzeFq1zGtFu12OQslMNbkSeEYywdB04t89/1O/w1cDnyilFU='; 
$channelSecret = 'b3ae34bda8a0a53b84a4ab8d11dc3106';
#-------------------------[Events]-------------------------#
$client = new LINEBotTiny($channelAccessToken, $channelSecret);
$userId     = $client->parseEvents()[0]['source']['userId'];
$groupId    = $client->parseEvents()[0]['source']['groupId'];
$replyToken = $client->parseEvents()[0]['replyToken'];
$timestamp  = $client->parseEvents()[0]['timestamp'];
$type       = $client->parseEvents()[0]['type'];
$message    = $client->parseEvents()[0]['message'];
$profile    = $client->profil($userId);
$repro = json_encode($profile);
$messageid  = $client->parseEvents()[0]['message']['id'];
$msg_type      = $client->parseEvents()[0]['message']['type'];
$msg_message   = $client->parseEvents()[0]['message']['text'];
$msg_title     = $client->parseEvents()[0]['message']['title'];
$msg_address   = $client->parseEvents()[0]['message']['address'];
$msg_latitude  = $client->parseEvents()[0]['message']['latitude'];
$msg_longitude = $client->parseEvents()[0]['message']['longitude'];
#----command option----#
$usertext = explode(":", $message['text']);
$command = $usertext[0];
$options = $usertext[1];

$modex = file_get_contents('./user/' . $userId . 'mode.json');


if ($modex == 'Normal') {
    $uri = "https://script.google.com/macros/s/AKfycby3Nam1j0Bcg4p5Hx_7mSK5Tt8evjosR3exq7uewxzH1q7RxP8/exec"; 
    $response = Unirest\Request::get("$uri");
    $json = json_decode($response->raw_body, true);
    $results = array_filter($json['user'], function($user) use ($command) {
    return $user['id'] == $command;
    }
  );

$i=0;
$bb = array();
foreach($results as $resultsz){
$bb[$i] = $resultsz;
$i++;
}


$textz .= "กรุณาระบุชื่อที่ต้องการค้นหา";
$textz .= "\n";
$textz .= $bb['0']['acess_no'];
$textz .= "\n";
$textz .= $bb['1']['name'];
$textz .= "\n";
$textz .= $bb['2']['name'];
$textz .= "\n";
$textz .= $bb['3']['name'];
$textz .= "\n";
$textz .= $bb['4']['name'];
    $mreply = array(
        'replyToken' => $replyToken,
        'messages' => array( 
          array(
                'type' => 'text',
                'text' => $textz
     )
     )
     );

$enbb = json_encode($bb);
    file_put_contents('./user/' . $userId . 'data.json', $enbb);
    file_put_contents('./user/' . $userId . 'mode.json', 'keyword');
}

elseif ($modex == 'keyword') {
    $urikey = file_get_contents('./user/' . $userId . 'data.json');
    $deckey = json_decode($urikey, true);

    $results = array_filter($deckey, function($user) use ($command) {
    return $user['name'] == $command;
    }
  );


$i=0;
$zaza = array();
foreach($results as $resultsz){
$zaza[$i] = $resultsz;
$i++;
}

$enzz = json_encode($zaza);
    file_put_contents('./user/' . $userId . 'data.json', $enzz);

$text .= "result";
$text .= "\n";
$text .= $zaza[0][id];
$text .= " - ";
$text .= $zaza[0][name];
$text .= " - ";
$text .= $zaza[0][num];
$text .= " - ";
$text .= $zaza[0][other];
    $mreply = array(
        'replyToken' => $replyToken,
        'messages' => array( 
          array(
                'type' => 'text',
                'text' => $text
     )
     )
     );

    file_put_contents('./user/' . $userId . 'mode.json', 'Normal');
}
else {
  file_put_contents('./user/' . $userId . 'mode.json', 'Normal');
}





if (isset($mreply)) {
    $result = json_encode($mreply);
    $client->replyMessage($mreply);
}  

?>