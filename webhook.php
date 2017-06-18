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
    "update_id": 235258818,
    "message": {
        "message_id": 4077,
        "from": {
            "id": 243692601,
            "first_name": "Ammar",
            "last_name": "Faizi",
            "username": "ammarfaizi2",
            "language_code": "en-US"
        },
        "chat": {
            "id": -1001114640699,
            "title": "Nocturnal Indonesia",
            "type": "supergroup"
        },
        "date": 1497175526,
        "text": "halo"
    }
}',1);
// */
/*
$telg->webhook_input = json_decode('{
    "update_id": 235259207,
    "message": {
        "message_id": 105,
        "from": {
            "id": 243692601,
            "first_name": "Ammar",
            "last_name": "Faizi",
            "username": "ammarfaizi2",
            "language_code": "en-US"
        },
        "chat": {
            "id": -1001143029479,
            "title": "a",
            "type": "supergroup"
        },
        "date": 1497766548,
        "new_chat_participant": {
            "id": 308645660,
            "first_name": "Kang IceTea",
            "username": "kang_icetea_bot"
        },
        "new_chat_member": {
            "id": 308645660,
            "first_name": "Kang IceTea",
            "username": "kang_icetea_bot"
        },
        "new_chat_members": [
            {
                "id": 308645660,
                "first_name": "Kang IceTea",
                "username": "kang_icetea_bot"
            }
        ]
    }
}
',1);*/

$tel = $telg->webhook_input;

if (isset($tel['message']['new_chat_member'])) {
    $name = $tel['message']['new_chat_member']['first_name'].(isset($tel['message']['new_chat_member']['last_name']) ? " ".$tel['message']['new_chat_member']['last_name'] : "");
    $nama_grup = $tel['message']['chat']['title'];
    $telg->sendMessage("Hai {$name}, selamat bergabung di {$nama_grup}, jangan lupa memperkenalkan diri :D", $tel['message']['chat']['id'], $tel['message']['message_id']);
} else
if (isset($tel['message']['text'])) {
	$actor = $tel['message']['from']['first_name'] . (isset($tel['message']['from']['last_name']) ? " ".$tel['message']['from']['last_name']:"");
	$from = $tel['message']['chat']['id'];
    $xa = $tel['message']['chat']['type']==="private";
    if ($xa) {
        $ai->turnOnSuggest();
    }
	$ai->prepare($tel['message']['text'], $actor);
	if($ai->execute()){
		$rep = $ai->fetch_reply();
	} elseif ($xa) {
		$rep = "Mohon maaf, saya belum mengerti \"".$tel['message']['text']."\"";
	}
}

if (isset($rep)) {
	if (is_array($rep)) {
		print $telg->sendMessage($rep[1], $from, $tel['message']['message_id']);
		print $telg->sendPhoto($rep[0], $from);
	} else {
        $rep = str_replace(array("<b>","</b>"),array("``b``","``/b``"), $rep);
        $rep = strip_tags($rep);
        $rep = str_replace(array("``b``","``/b``","/home/ice/public/.webhooks/telegram/crayner/data/ai/php_virtual/tmp/"), array("<b>","</b>","/tmp/php_virtual/"), $rep);
		print $telg->sendMessage($rep, $from, $tel['message']['message_id']);
	}
}
