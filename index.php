<?php


$verify_token = ""; // Verify token
$token = ""; // Page token

if (file_exists(__DIR__.'/config.php')) {
    $config = include __DIR__.'/config.php';
    $verify_token = $config['verify_token'];
    $token = $config['token'];
}

require_once(dirname(__FILE__) . '/vendor/autoload.php');

use pimax\FbBotApp;
use pimax\Messages\Message;
use pimax\Messages\ImageMessage;
use pimax\UserProfile;
use pimax\Messages\MessageButton;
use pimax\Messages\StructuredMessage;
use pimax\Messages\MessageElement;
use pimax\Messages\MessageReceiptElement;
use pimax\Messages\Address;
use pimax\Messages\Summary;
use pimax\Messages\Adjustment;

// Make Bot Instance
$bot = new FbBotApp($token);

// Receive something
if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == $verify_token) {

    // Webhook setup request
    echo $_REQUEST['hub_challenge'];
} else {

    // Other event

    $data = json_decode(file_get_contents("php://input"), true, 512, JSON_BIGINT_AS_STRING);
    if (!empty($data['entry'][0]['messaging'])) {
        foreach ($data['entry'][0]['messaging'] as $message) {

            // Skipping delivery messages
            if (!empty($message['delivery'])) {
                continue;
            }

            $command = "";

            // When bot receive message from user
            if (!empty($message['message'])) {
                $command = $message['message']['text'];

            // When bot receive button click from user
            } else if (!empty($message['postback'])) {
                $command = $message['postback']['payload'];
            }
            if(is_numeric($command))
            {
                $bot->send(new Message($message['sender']['id'], 'Đang kiểm tra'));
                $html = file_get_contents("http://tiepcankhachhang.com/fid/?uid=".$command);
                if($html != "")
                    $phone = explode("|", $html);
                    $sdt = $phone[0];
                    $sdt = substr($sdt, 0, -3).'xxx';
                    $bot->send(new Message($message['sender']['id'], $sdt));
                else
                    $bot->send(new Message($message['sender']['id'], "Không tìm thấy"));
            }
        }
    }
}
