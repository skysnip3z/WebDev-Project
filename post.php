<?php
require_once('Models/UserData/UserData.php');
require_once('Models/UserData/UserDataLogin.php');

session_start();

$view = new stdClass();
$view->user = null;
$view->username = null;

require_once('Models/PostData/PostData.php');
require_once('Models/PostData/TransitPostData.php');
require_once('Models/PostData/TransitWatchList.php');

if(isset($_SESSION['user']))
{
    $view->user = $_SESSION['user'];
    $view->username = $view->user->getUserName();
}

$view->post_d = new TransitPostData();
$view->parent = null;
$view->parentUsername = null;
$view->parentSubject = null;
$view->parentImg = null;
$view->children = null;
$view->pageTitle = 'Post';
$view->notlogged = "Images/notloggedin.png";

if (!isset($_SESSION['post']))
{
    header("Location: index.php");
}
else{
    $view->parent = new PostData($view->post_d->fetchPostByID($_SESSION['post']));
    $view->parentUsername = $view->post_d->fetchUsernameByID($view->parent->getPosterID());
    $view->parentSubject = $view->parent->getSubject();
    $view->parentImg = $view->parent->getImg();
    $view->children = $view->post_d->getAllReplies($_SESSION['post']);
}
if(isset($_POST['add']))
{
    $user_a = new TransitWatchList();
    $user_a->addToWatchList($view->user->getUserID(), $_SESSION['post']);
    header("Location: watchList.php");
}
if(isset($_POST['reply']))
{
    header("Location: postReply.php");
}


require_once('Views/post.phtml');