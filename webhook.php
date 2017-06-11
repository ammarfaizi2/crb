<?php
require __DIR__ . "/vendor/autoload.php";
use Telegram\Telegram;
use AI\AI;
header("Content-type:application/json");
define("data", __DIR__ . "/data");
is_dir(data) or mkdir(data);

$telg = new Telegram("348646582:AAGjRQ6eW-WjVqInD_rwwocMjy3Kk--Rblg");
$ai	  = new AI();

/* // Debugging
$telg->webhook_input = json_decode('{
    "update_id": 235258797,
    "message": {
        "message_id": 15151,
        "from": {
            "id": 243692601,
            "first_name": "Ammar",
            "last_name": "Faizi",
            "username": "ammarfaizi2",
            "language_code": "en-US"
        },
        "chat": {
            "id": 243692601,
            "first_name": "Ammar",
            "last_name": "Faizi",
            "username": "ammarfaizi2",
            "type": "private"
        },
        "date": 1497173119,
        "text": "halsadfasdo"
    }
}',1);

// */

$tel = $telg->webhook_input;
if (isset($tel['message']['text'])) {
	$actor = $tel['message']['from']['first_name'] . (isset($tel['message']['from']['last_name']) ? " ".$tel['message']['from']['last_name']:"");
	$from = $tel['message']['from']['id'];
	$ai->prepare($tel['message']['text'], $actor);
	if($ai->execute()){
		$rep = $ai->fetch_reply();
	} elseif ($tel['message']['chat']['type']==="private") {
		$rep = "Mohon maaf, saya belum mengerti \"".$tel['message']['text']."\"";
	}
}

if (isset($rep)) {
	if (is_array($rep)) {
		print $telg->sendMessage($rep[1], $from, $tel['message']['message_id']);
		print $telg->sendPhoto($rep[0], $from);
	} else {
		print $telg->sendMessage($rep, $from, $tel['message']['message_id']);
	}
}
