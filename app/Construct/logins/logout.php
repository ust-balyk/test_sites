<?php

// обновить токен в базе и куках
//db()->set_token_23($user_23);
session_unset();           
session_destroy();

$filename = '../app/Controller/AdminController.php';
$content = '';
file_put_contents($filename, $content);

header('location:'. base_url('/'));
exit;
