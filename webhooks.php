<?php // callback.php


define('LINE_API',"https://notify-api.line.me/api/notify");
 
$token = "Fvt1yyoJCE9x0f8iPgvSBHnxXPKIVi2AY8OX8n9nn7E"; //ใส่Token ที่copy เอาไว้


require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$access_token = 'QLDnfbIa7jW8gti3qLkYN8OGlSeJDQBG40njsGSiogG/NbWSnvba7Ie+hEfAs1gFjvItgPpMNzCZBBorvpm3ToD7zHZ0VacRM5FAs4eA5M/V9N8rtg4F/6l5k0wY6DGnfCHtEKQek2uCuUYgqE5lswdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		$text = $event['message']['id'];
		$mysqltext = '';
		$mysqltype = "0";
		
		
		
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
		      	$text = "ได้รับข้อความ".$event['message']['text']."เรียบร้อยแล้ว อย่าลืมแจ้งชื่อลูกค้าด้วย...\n".$text;
			$mysqltext = $event['message']['text'];
			$mysqltype = "0";
		
		        $res = notify_message($mysqltext."\n".$event['source']['userId'],$token);
                        //print_r($res);

		}

		if ($event['type'] == 'message' && $event['message']['type'] == 'image') {
			$text = "ได้รับรูปภาพ"."เรียบร้อยแล้ว อย่าลืมแจ้งชื่อลูกค้าด้วย...\n".$text;
			$mysqltext = "Image";
			$mysqltype = "1";
			
			$res = notify_message('https://api-data.line.me/v2/bot/message/'.$event['message']['id'].'/content'."\n\n".'http://leemotorsales.com/line/getpicture.php?url='.$event['message']['id']."\n".$event['source']['userId'],$token);
			//$text = $text . ' https://api.line.me/v2/bot/message/'.$event['message']['id'].'/content';
		}

		if ($mysqltext != '') { 
		
	        //$con = mysqli_connect('27.mysql.servage.net', '1010309_ga44973', 'Bkoil001', '1010309-transfernote ');
		//$con->set_charset("utf8");
		//$con->set_charset("tis620");			
		
		
	        //$query = "INSERT INTO transfer (f_datetime,f_message_id,f_type,f_text,f_note) VALUES (now(),'".$event['message']['id']."',";
		//   $query = $query.$mysqltype.",'".$mysqltext."','');";
	        
		//$text = $text.$query;
	        //$result = mysqli_query($con, $query);
	    
		} else {
		   $text = "ไม่เข้าใจ";
		}	
		
		
			// Get text sent
			//$text = $event['source']['userId'];
			
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";		
	
	}
}
echo "OOK";


function notify_message($message,$token){
 $queryData = array('message' => $message);
 $queryData = http_build_query($queryData,'','&');
 $headerOptions = array( 
         'http'=>array(
            'method'=>'POST',
            'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
                      ."Authorization: Bearer ".$token."\r\n"
                      ."Content-Length: ".strlen($queryData)."\r\n",
            'content' => $queryData
         ),
 );
 $context = stream_context_create($headerOptions);
 $result = file_get_contents(LINE_API,FALSE,$context);
 $res = json_decode($result);
 return $res;
}   
