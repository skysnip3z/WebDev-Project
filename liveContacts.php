<?php
require_once('Models/ContactData/TransitContactSearch.php');

$tcs = new TransitContactSearch();
$contacts = $tcs->getContacts($tcs->cleanInput($_REQUEST['c']));
echo json_encode($contacts);
