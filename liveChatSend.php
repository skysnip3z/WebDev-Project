<?php
require_once('Models/MsgData/TransitMsg.php');

$tm = new TransitMsg();

$uf = $_POST['user_from'];
$ut = $_POST['user_to'];
$mb = $_POST['msg_body'];

$tm->sendMsg($uf, $ut, $tm->cleanInput($mb));

echo "Msg Sent";