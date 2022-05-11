<?php
require_once('Models/MsgData/TransitMsg.php');

$tm = new TransitMsg();
$a = $tm->cleanInput($_REQUEST['f']);
$b = $tm->cleanInput($_REQUEST['t']);
$tm->updateLastAccess($a, $b);

$messages = $tm->getConversation($a, $b);

echo json_encode($messages);