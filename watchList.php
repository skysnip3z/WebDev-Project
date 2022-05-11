<?php
require_once('Models/UserData/UserData.php');
require_once('Models/UserData/UserDataLogin.php');

session_start();

$view = new stdClass();
$view->user = null;
$view->username = null;

require_once('Models/PostData/PostDisplay.php');
require_once('Models/PostData/TransitPostData.php');
require_once('Models/PostData/TransitPostSearch.php');
require_once('Models/PostData/TransitWatchList.php');

if(isset($_SESSION['user']))
{
    $view->user = $_SESSION['user'];
    $view->username = $view->user->getUserName();
}

$post_wl = new TransitWatchList();
$post_s = new TransitPostSearch();
$view->posts = null;
$view->pageTitle = 'Search';
$view->posts = $post_wl->fetchWatchList($view->user->getUserID());

if(isset($_POST['post_id']))
{
    $_SESSION['post'] = $_POST['post_id'];
    header("Location: post.php");
}
else{
    $_SESSION['post'] = null;
}


require_once('Views/watchList.phtml');
