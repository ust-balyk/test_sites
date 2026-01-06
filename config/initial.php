<?php
const DEBUG = true;
define("ROOT", dirname(__DIR__));
const PATH        = 'http://localhost:8888';
const ADMIN       = PATH .'/entry';
const APP         = ROOT .'/app';
const CORE        = ROOT .'/core';
const ROUTES      = ROOT .'/config/routes.php';
const HELPER      = ROOT .'/helper/functions.php';
const AUTO_LOAD   = ROOT .'/vendor/autoload.php';
const WWW         = ROOT .'/public'; 
const ERROR_LOGS  = ROOT .'/log/errors/error.log';
const CACHE       = ROOT .'/log/cache';
const ACCOUNT     = APP  .'/Account';
const CONSTRUCT   = APP  .'/Construct';
const LAYOUTS     = APP  .'/Construct/layouts';
const DEF_LAYOUT  = APP  .'/Construct/layouts/main.php';
const VIEWS       = APP  .'/Construct/views';
const DEF_VIEW    = APP  .'/Construct/views/main.php';
########

const DB_SETTINGS = [
   'host'         => 'localhost',   
   'database'     => 'japan_in_ru',
   'username'     => 'root',
   'password'     => 'root',
   'charset'      => 'utf8mb4',
   'options'      => [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
   ],
];

########
const CLOSED_FOR = [
   'friend' => \App\Lock\Friend::class,
   'guest'  => \App\Lock\Guest::class,
   'all'    => \Master\Administrator::class,
];

########
const ADMIN_A = [
   'test@test.ru',
];

######## 
const HOME_LAYOUT = '';
const HOME_VIEW   = '';
const PAGE_LAYOUT = 'category';
const PAGE_VIEW   = 'category';
$POCKET_STYLE     = '';
$MENU_STYLE       = '';

if ( $POCKET_STYLE == false || $POCKET_STYLE === 'main' ) {
   $POCKET_STYLE = '/public/main_pocket';
} else { 
   $POCKET_STYLE = '/public/start_pocket';
}
define( 'POCKET_STYLE', $POCKET_STYLE );

if ( $MENU_STYLE == false || $MENU_STYLE === 'main' ) {
   $MENU_FOR_TABLE = 'categories';
   $MENU_TEMPLATE  = 'categories_menu';
   $MENU_CACHE_KEY = 'categories_menu';
} else {
   $MENU_FOR_TABLE = 'cosmetics';
   $MENU_TEMPLATE  = 'cosmetics_menu';
   $MENU_CACHE_KEY = 'cosmetics_menu';
}
define( 'MENU_FOR_TABLE', $MENU_FOR_TABLE );
define( 'MENU_TEMPLATE',   $MENU_TEMPLATE );
define( 'MENU_CACHE_TIME',              0 );
define( 'MENU_CACHE_KEY', $MENU_CACHE_KEY );

########
const PAGINATION_SETTINGS = [
   'totalRecords'    => 0,  // увеличить чтобы изменить
   'linesOnPage'     => 4,  // количество выводов на странице
   'requestInterval' => 3,  // количество ссылок вокруг центральной 
   'startPaging'     => 8,  // количество страниц с которых начинается paging
   'template'        => '/pagination/base-1',
];

########
