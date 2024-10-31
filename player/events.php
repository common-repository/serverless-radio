<?php

include("config.php");

//header("Content-Type: text/event-stream");

header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache");
header("Access-Control-Allow-Origin: *");
header( 'Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE' );
header( 'Access-Control-Allow-Credentials: true' );


/*$lastEventId = floatval(isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? $_SERVER["HTTP_LAST_EVENT_ID"] : 0);
if ($lastEventId == 0) {
$lastEventId = floatval(isset($_GET["lastEventId"]) ? $_GET["lastEventId"] : 0);
}

echo ":" . str_repeat(" ", 2048) . "\n"; // 2 kB padding for IE
echo "retry: 2000\n";

// event-stream
$i = $lastEventId;*/

function sendMsg($startedAt, $slrtick) {

//$id = time();
$mp3dj = slr_get_current_song($startedAt, $slrtick);
$mp3 = $mp3dj['title'];
$tick = abs($mp3dj['tick']);
$id = $mp3dj['id'];
$next = $mp3dj['next'];
$prev1 = $mp3dj['prev1'];
$prev2 = $mp3dj['prev2'];


/*echo "id: $id" . PHP_EOL;
echo "data: {\n";
echo "data: \"song\": \"$mp3\", \n";
echo "data: \"tick\": \"$tick\", \n";
echo "data: \"id\": $id, \n";
echo "data: \"next\": \"$next\", \n";
echo "data: \"prev1\": \"$prev1\", \n";
echo "data: \"prev2\": \"$prev2\" \n";
echo "data: }\n";
echo PHP_EOL;
ob_flush();
flush();*/

$echo = array("id" => $id, "song" => $mp3, "tick" => $tick, "next" => $next, "prev1" => $prev1, "prev2" => $prev2, "live" => false);

echo json_encode($echo); 


}



sendMsg($startedAt, $slrtick);


?>