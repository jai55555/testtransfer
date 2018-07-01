<?php // callback.php

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
		
		
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
		      	$text = "ได้รับข้อความ".$text . $event['message']['text']."เรียบร้อยแล้ว";
			$mysqltext = $event['message']['text'];
		}

		if ($event['type'] == 'message' && $event['message']['type'] == 'image') {
			$text = "ได้รับรูปภาพ". $text . "เรียบร้อยแล้ว";
			$mysqltext = "Image";
			//$text = $text . ' https://api.line.me/v2/bot/message/'.$event['message']['id'].'/content';
		}

		if ($mysqltext != '') { 
		
	        $con = mysqli_connect('remote-mysql3.servage.net', 'transfernote', 'Bkoil001', 'transfernote');
		$con->set_charset("utf8");
		
		
	        $query = "INSERT INTO transfer (f_datetime,f_message_id,f_type,f_text,f_note) VALUES (now(),'".$event['message']['id']."','";
		   $query = $query.$event['message']['type']."','".$mysqltext."','');";
	        
		//$text = $text.$query;
	        $result = mysqli_query($con, $query);
	    
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
echo "OK";
