<?php
require_once('Models/PostData/TransitPostSearch.php');

$tps = new TransitPostSearch();

// Clean input and find search results
$searchArr = $tps->findLiveWordMatch($tps->cleanInput($_REQUEST["s"]));

echo json_encode($searchArr);