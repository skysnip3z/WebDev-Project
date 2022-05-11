<?php
require_once('Models/UserData/UserData.php');
require_once('Models/UserData/UserDataLogin.php');

session_start();

$view = new stdClass();
$view->user = null;
$view->username = null;
$img = "NULL";
$tmpImg = null;
require_once ('Models/PostData/TransitPostAdd.php');

$view->pageTitle = "Posting";
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


if(isset($_POST['submit']))
{
    if(isset($_FILES['imageIn'])) {
        $allowable = array("jpg", "png", "jpeg", "");

        $f_name = strtolower($_FILES['imageIn']['name']);
        $f_size = $_FILES['imageIn']['size'];
        $f_tmp = $_FILES['imageIn']['tmp_name'];
        $f_type = $_FILES['imageIn']['type'];
        $ext = pathinfo($f_name, PATHINFO_EXTENSION);

        if (in_array($ext, $allowable) === false)
        {
            $view->error="err_file_format";
        }

        if ($f_size > 2000000) {
            $view->error = 'err_file_size';
        }

        $img = $f_name;
        $tmpImg = $f_tmp;
    }
    // Trap
    $post_important = $post_a->cleanInput($_POST['post_important']);

    if ($post_important == null && $view->error == null) {

        $poster_id = $post_a->getUserIDByUsername($view->username);
        $subcat_id = $post_a->cleanInput($_POST['subcat']);
        $subject = $post_a->cleanInput($_POST['subject']);
        $post_body = $post_a->cleanInput($_POST['post_body']);

        move_uploaded_file($tmpImg, "Images/" . $img);

        $post_a->createPost($poster_id, $subcat_id, $subject, $post_body, $img);
    }
}


require_once ('Views/postAdd.phtml');
