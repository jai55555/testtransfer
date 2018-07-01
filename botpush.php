<?php



require "vendor/autoload.php";

$access_token = 'QLDnfbIa7jW8gti3qLkYN8OGlSeJDQBG40njsGSiogG/NbWSnvba7Ie+hEfAs1gFjvItgPpMNzCZBBorvpm3ToD7zHZ0VacRM5FAs4eA5M/V9N8rtg4F/6l5k0wY6DGnfCHtEKQek2uCuUYgqE5lswdB04t89/1O/w1cDnyilFU=';

$channelSecret = 'bac0b363c9fecf80deeb3055bccfaf96';

$pushID = 'Ucce7db8c05ccddefb9def3741d1df993';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);

$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello world');
$response = $bot->pushMessage($pushID, $textMessageBuilder);

echo $response->getHTTPStatus() . ' ' . $response->getRawBody();







