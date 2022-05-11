<?php
require_once('Models/MsgData/TransitMsg.php');

$tm = new TransitMsg();
$a = $tm->cleanInput($_REQUEST['a']); // UID me
$b = $tm->cleanInput($_REQUEST['b']); // UID user_b

$tm->setConversation($a, $b, true);
$tm->setConversation($b,$a, false);
$tm->startConversation($a,$b);
$tm->startConversation($b, $a);
