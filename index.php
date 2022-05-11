<!-- Date Current Build: 27/04/2020 -->
<?php
require_once('Models/UserData/UserData.php');
require_once('Models/UserData/UserDataLogin.php');
require_once('Models/PostData/PostDisplay.php');
require_once('Models/PostData/TransitPostData.php');

session_start();

$view = new stdClass();
$view->user = null;
$view->username = null;




if(isset($_SESSION['user']))
{
    $view->user = $_SESSION['user'];
    $view->username = $view->user->getUserName();
}

$view->pageTitle = 'Homepage';
$view->post_user = null;
$post_d = new TransitPostData();

$view->subcat_one = $post_d->getAllParentDisplays(1);
$view->subcat_two = $post_d->getAllParentDisplays(2);

if(isset($_POST['post_id']))
{
    $_SESSION['post'] = $_POST['post_id'];
    header("Location: post.php");
}
else{
    $_SESSION['post'] = null;
}

require_once('Views/index.phtml');