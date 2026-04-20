<?php
//$start_time = microtime(true);

if (PHP_MAJOR_VERSION < 8) {
   die("<pre><br>  Текущая версия PHP: " . phpversion() .
       "<br>Требуется версия >= 8</pre>");
}

include "../config/initial.php";
include AUTO_LOAD;
include HELPER;

$whoops = new \Whoops\Run();
if (DEBUG) {   
   $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
} else {
   $whoops->pushHandler(new \Whoops\Handler\CallbackHandler(function (Throwable $e) {
      error_log("[" . date('Y-m-d H:i:s') . "] Error: {$e->getMessage()}" .
         PHP_EOL . "File: {$e->getFile()}" . "Line: {$e->getLine()}" .
         PHP_EOL . "****************************************************" .
         PHP_EOL, 3, ERROR_LOGS);
      abort(500);
   }));
}
$whoops->register(); 

$app = new Master\Application();
include ROUTES;
$app->run();

//dump("время выполнения запроса: " . microtime(true) - $start_time);
