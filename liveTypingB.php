<?php
// Check B is typing - intervals ~ make efficient
require_once('Models/MsgData/TransitMsg.php');

$tm = new TransitMsg();
$b = $_REQUEST['b'];
$typing = $tm->checkTyping($b);

echo json_encode($typing);