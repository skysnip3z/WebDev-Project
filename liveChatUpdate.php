<?php
require_once('Models/MsgData/TransitMsg.php');

$tm = new TransitMsg();

$a = $_REQUEST['a']; // Logged in user
$b = $_REQUEST['b']; // Chatting to user
$t = $_REQUEST['t']; // timestamp of last message

$arr = str_split($t, 1);
$ts = "20" . $arr[15] . $arr[16] . "-" . $arr[12] . $arr[13] . "-" . $arr[9] . $arr[10]
    . " " . $arr[0] . $arr[1] . ":" . $arr[3] . $arr[4] . ":" . $arr[6] . $arr[7];

$update = $tm->refreshConv($a, $b, $ts);

echo json_encode($update);

