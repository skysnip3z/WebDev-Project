<?php
require_once('Models/UserData/UserData.php');
require_once('Models/UserData/UserDataLogin.php');

session_start();

$view = new stdClass();
$view->user = null;
$view->username = null;

// To ensure sighup.php is not manually accessible to logged in user
if (isset($_SESSION['user']))
{
    header("Location: index.php");
}

require_once('Models/UserData/UserRegister.php');

$view->pageTitle = 'Sign Up';
$view->error = null;

if (isset($_POST['submit']))
{
    $user_r = new UserRegister($_POST['email1'],($_POST['email2']), ($_POST['password1']),
        ($_POST['password2']),($_POST['username']));

    $view->error = $user_r->signup();
}

require_once('Views/signup.phtml');
