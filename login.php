<?php
require_once('Models/UserData/UserDataLogin.php');

session_start();

$view = new stdClass();
$view->user = null;
$view->username = null;
// To ensure login.php is not manually accessible to logged in user
if (isset($_SESSION['user']))
{
    header("Location: index.php");
}
require_once('Models/UserData/UserLogin.php');
$user = new UserLogin();

$view->pageTitle = "Log In";
$view->err_email = null;
$view->err_password = null;

if(isset($_POST['submit']))
{
    $output = $user->login($_POST['email'],$_POST['password']);

    switch ($output)
    {
        case $output == "err_email":
            $view->err_email = true;
            break;
        case $output == "err_password":
            $view->err_password = true;
            break;
        case is_object($output) == true:
            $_SESSION['user'] = $output;
            header("Location: index.php");
            break;
    }
}
require_once('Views/login.phtml');