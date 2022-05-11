<?php
require_once('Models/UserData/UserData.php');
require_once('Models/UserData/UserDataLogin.php');

session_start();

if(!isset($_SESSION['post']))
{
    header("Location: index.php");
}

$view = new stdClass();
$view->user = null;
$view->username = null;
require_once ('Models/PostData/TransitPostAdd.php');

$view->pageTitle = "Replying";
$view->error = null;
$acceptable = array(IMAGETYPE_JPEG, IMAGETYPE_PNG);
$post_a = new TransitPostAdd();


if(isset($_SESSION['user']))
{
    $view->user = $_SESSION['user'];
    $view->username = $view->user->getUserName();
}else{
    header("Location: index.php");
}



if(isset($_POST['submit']) && isset($_SESSION['post']))
{
    $temp = $_SESSION['post'];
    $poster_id = $post_a->getUserIDByUsername($view->username);
    $subcat_id = $post_a->getSubcatIDByPostID($temp);
    $parent_id = $temp;
    $post_body = $post_a->cleanInput($_POST['post_body']);

    // Trap for bots
    $post_important = $post_a->cleanInput($_POST['post_important']);

    if($post_important == null && $view->error == null)
    {

        $post_a->replyToPost($poster_id, $subcat_id, $parent_id, $post_body);
        header("Location: index.php");

    }else{
        $view->error = "Please contact admin for further help"; // Not giving bots any error info for trap
    }
}


require_once ('Views/postReply.phtml');
