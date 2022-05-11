<?php
require_once('Models/UserData/UserDataLogin.php');

session_start();

$view = new stdClass();
$view->user = null;
$view->username = null;

require_once('Models/PostData/PostSearch.php');
require_once('Models/PostData/PostDisplay.php');
require_once('Models/PostData/TransitPostData.php');
require_once('Models/PostData/TransitPostSearch.php');

if(isset($_SESSION['user']))
{
    $view->user = $_SESSION['user'];
    $view->username = $view->user->getUserName();
}

$post_s = new TransitPostSearch();
$view->posts = null;
$view->pageTitle = 'Search';

if(isset($_POST['submit']))
{
    $cleanStr = $post_s->cleanInput($_POST['search']);
    $subcatVal = $_POST['subcat'];

    switch ($subcatVal)
    {
        case "0":
            $view->posts = $post_s->findWordMatch($cleanStr);
            break;
        case "1":
        case "2":
            $view->posts = $post_s->findWordMatchByCategory($subcatVal, $cleanStr);
            break;
    }

}

if(isset($_POST['post_id']))
{
    $_SESSION['post'] = $_POST['post_id'];
    header("Location: post.php");
}
else{
    $_SESSION['post'] = null;
}


require_once('Views/search.phtml');
