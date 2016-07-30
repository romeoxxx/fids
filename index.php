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
                //$bot->send(new Message($message['sender']['id'], $command));
                if($command == "Mã đăng ký")
                    $command = '/key';
                if($command == "Thông tin đăng ký")
                    $command = '/lic';
                if($command == "Lịch sử tìm kiếm")
                    $command = '/his';
            }


            if($command == '/help'){
                $bot->send(new StructuredMessage($message['sender']['id'],
                        StructuredMessage::TYPE_GENERIC,
                        [
                            'elements' => [
                                 new MessageElement("Cú pháp tìm kiếm:", "Gửi uid mà bạn muốn tìm ví dụ: 10000633388888", "http://i.imgur.com/U0RzjPV.jpg", [
                                    new MessageButton(MessageButton::TYPE_POSTBACK, 'Mã đăng ký'),
                                    new MessageButton(MessageButton::TYPE_POSTBACK, 'Thông tin đăng ký'),
                                    new MessageButton(MessageButton::TYPE_POSTBACK, 'Lịch sử tìm kiếm')
                                ]),
                            ]
                        ]
                    ));
            }
            if($command == '/his'){
                $html = file_get_contents('http://'.'tiepcan'.'khachhang'.'.com/fid/his.php?fid='.$message['sender']['id']); 
                    $bot->send(new Message($message['sender']['id'], $html));

            }
            if($command == '/key'){
                $bot->send(new StructuredMessage($message['sender']['id'],
                        StructuredMessage::TYPE_GENERIC,
                        [
                            'elements' => [
                                new MessageElement('Mã đăng ký: '.$message['sender']['id'], ".", "", null)
                            ]
                        ]
                    ));
                
            }

            if($command == '/lic' && $command != '/lic '){
                $html = file_get_contents('http://'.'tiepcan'.'khachhang'.'.com/fid/lic.php?fid='.$message['sender']['id']); 
                $bot->send(new Message($message['sender']['id'], $html));
            }

            if (strpos($command, '/dk ') !== false) {
                if($message['sender']['id'] == '1196611687050818' || $message['sender']['id'] == '1099546280107865'){
                    $val = explode(" ", $command);
                    $url = 'http://'.'tiepcan'.'khachhang'.'.com/fid/add.php?fid='.$val[1].'&balance='.$val[2].'&memo='.urlencode($command);
                    $html = file_get_contents($url); 
                    $bot->send(new Message($message['sender']['id'], $html));
                }
                else
                {
                    $bot->send(new Message($message['sender']['id'], 'Bạn không có quyền sử dụng tính năng này.'));
                }
                
            }


            if (strpos($command, '/lic ') !== false) {
                if($message['sender']['id'] == '1196611687050818' || $message['sender']['id'] == '1099546280107865'){
                    $val = explode(" ", $command);
                    $html = file_get_contents('http://'.'tiepcan'.'khachhang'.'.com/fid/lic.php?fid='.$val[1]); 
                    $bot->send(new Message($message['sender']['id'], $html));
                }
                else
                {
                    $bot->send(new Message($message['sender']['id'], 'Bạn không có quyền sử dụng tính năng này.'));
                }
                
            }



            if(is_numeric($command))
            {          	
                if(strlen($command) < 9)
            	{
            		//$bot->send(new Message($message['sender']['id'], $command.' có thể không phải là UID'));	
            	}
            	else
            	{
	                $bot->send(new Message($message['sender']['id'], 'Đang kiểm tra UID: '.$command));
	                $html = file_get_contents('http://'.'tiepcan'.'khachhang'.'.com/fid/?uid='.$command.'&fid='.$message['sender']['id']);
	                if($html != "")
	                {
                    	$bot->send(new Message($message['sender']['id'], $html));
                            
	                }
	                else
                    	$bot->send(new Message($message['sender']['id'], "Không tìm thấy" ));
            	}
            }
        }
    }
}
