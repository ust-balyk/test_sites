<?php
session_unset();           
session_destroy();

/*
app()->session->remove($_SESSION["csrf_token"]);
$_SESSION["csrf_token"] = bin2hex(random_bytes(32));
 */

$filename = '../app/Controller/AdminController.php';
$content = '';
file_put_contents($filename, $content);

//echo "<script>window.history.back(); return false;</script>";


if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
    header('location:'. $_SERVER['HTTP_REFERER']);

} else {
    header('location:'. base_url('/'));

}
exit;
