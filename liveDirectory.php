<?php
require_once('Models/ContactData/TransitContactSearch.php');

$tcs = new TransitContactSearch();
$u = $_REQUEST['u'];

$directory = $tcs->getDirectory($u);

echo json_encode($directory);

