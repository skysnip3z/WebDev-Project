<?php
// User Typing
require_once('Models/MsgData/TransitMsg.php');

$tm = new TransitMsg();
$a = $_REQUEST['a'];
$tm->startTyping($a);
