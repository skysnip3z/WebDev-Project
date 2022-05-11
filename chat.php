<?php
require_once('Models/UserData/UserData.php');
require_once('Models/UserData/UserDataLogin.php');

session_start();

$view = new stdClass();
$view->user = null;
$view->username = null;
$view->userID = null;
$img = "NULL";
$tmpImg = null;
$view->pageTitle = "Chatting";
$view->error = null;

if(isset($_SESSION['user']))
{
    $view->user = $_SESSION['user'];
    $view->username = $view->user->getUserName();
    $view->userID = $view->user->getUserID();
}else{
    header("Location: index.php");
}

require_once ('Views/chat.phtml');
