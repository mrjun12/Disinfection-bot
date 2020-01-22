<?php
/**
 * Create By MrJun
 * 赞助商:黑店（https://taoluyun.cc）
 * 效果预览:t.me/ExpressNode
 */

define("SLEEPTIME",300);// 消毒时间 单位：S
define("TOKEN","<Bot Token>"); 	//Bot Token
define("CHATID","<群组ID>"); 	//群组ID

class TelegramBot
{

	private $url;
	private $chat_id;
	
	function __construct( $token , $chat_id){

		$this->url = 'https://api.telegram.org/bot'.$token.'/';;
		$this->chat_id = $chat_id;

	}

	function setChatPermissions( $bool ){

		$content = $this->getChatPermissions($bool);

		return $this->sendApi( "setChatPermissions" , $content );

	}

	function sendMessage( $text ){

		$content = [ "chat_id" => $this->chat_id , "text" => $text ];

		return $this->sendApi('sendMessage', $content);

	}

	function getChatPermissions( $bool ){

		$permissions = [
			"can_send_messages" => $bool,
			"can_send_media_messages" => $bool,
			"can_send_polls" => $bool,
			"can_send_other_messages" => $bool,
			"can_add_web_page_previews" => $bool,
			"can_invite_users" => $bool
		];

		$content = [
			"chat_id" => $this->chat_id,
			"permissions" => json_encode($permissions)
		];

		return $content;

	}

	function sendApi( $api , $content = [] ){

		$url = $this->url . $api;

		return $this->post( $url , $content);

	}

	function post( $url, $content = [] ){

		if (isset($content['chat_id'])) {
            $url = $url.'?chat_id='.$content['chat_id'];
            unset($content['chat_id']);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($ch);
        if ($result === false) {
            $result = json_encode(['ok'=>false, 'curl_error_code' => curl_errno($ch), 'curl_error' => curl_error($ch)]);
        }
        curl_close($ch);
        return $result;

	}
}


$Bot = new TelegramBot( TOKEN, CHATID);

$Bot->sendMessage( "群内消毒中，请戴好口罩不要说话。" );
$Bot->setChatPermissions( false );
$Bot->sendMessage( "说已开启紫外线杀菌。" );
$Bot->sendMessage( "正在喷洒次氧化氯溶液消毒中。" );
sleep(SLEEPTIME);
$Bot->setChatPermissions( true );
$Bot->sendMessage( "消毒完毕，正在开窗通风。" );
