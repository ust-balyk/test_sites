<?php
const DEBUG = true;
define("ROOT", dirname(__DIR__));
const PATH        =       'http://localhost:8888';
const SITE_NAME   =       'Japan-in.Ru';
const LOCALE      =       'ru_RU';
const ADMIN       = PATH .'/entry';
const APP         = ROOT .'/app';
const CORE        = ROOT .'/core';
const ROUTES      = ROOT .'/routes/routes.php';
const UTILS       = ROOT .'/utils/functions.php';
const AUTO_LOAD   = ROOT .'/vendor/autoload.php';
const WWW         = ROOT .'/public';
const CACHE_DB    = ROOT .'/cache/db/cache_db.json';
const CACHE_MENU  = ROOT .'/cache/menu';
const CACHE       = ROOT .'/cache/temp';
const ERROR_LOGS  = ROOT .'/log/errors/error.log';
const ACCOUNT     = APP  .'/Account';
const CONSTRUCT   = APP  .'/Construct';
const LAYOUT      = APP  .'/Construct/layouts';
const DEF_LAYOUT  = APP  .'/Construct/layouts/main.php';
const VIEW        = APP  .'/Construct/views';
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
   'frend' => \App\Lock\Friend::class,
   'guest' => \App\Lock\Guest::class,
   'all'   => \Master\Administrator::class,
];

######## administrator
const EMAIL    = 'ust.balyk@gmail.com';
const PASSWORD = 'vjqZpsr8Ytvfzhs,f';

######## сложность password 
const TIME_COST = 0.200;

######## assistant
const ADMIN_A = [
   'test@test.ru',
];

######## 
const HOME_LAYOUT     = 'start';
const HOME_VIEW       = 'start';
const CATEGORY_LAYOUT = 'category';
const CATEGORY_VIEW   = 'category';
const PRODUCT_LAYOUT  = 'product';
const PRODUCT_VIEW    = 'product';
##
const DEFAULT_LAYOUT  = 'default';

$POCKET_STYLE = 'start';
if ( $POCKET_STYLE  == false || $POCKET_STYLE  === 'main' ) {
   $POCKET_STYLE = '/public/main_pocket';
   $TABLE_NAME   = 'products';
} else { 
   $POCKET_STYLE = '/public/start_pocket';
   $TABLE_NAME   = 'cosmetics';
}
define( 'POCKET_STYLE', $POCKET_STYLE );
define( 'TABLE_NAME',   $TABLE_NAME );
##

$MENU_TABLE           = 'start';        //'products';
$MENU_TEMPLATE        = 'partial_menu'; //'full_menu';
$MENU_CACHE_KEY       = 'partial_menu'; //'full_menu';
const MENU_CACHE_TIME =  86400;          //31536000;
define( 'MENU_TABLE',     $MENU_TABLE );
define( 'MENU_TEMPLATE',  $MENU_TEMPLATE );
define( 'MENU_CACHE_KEY', $MENU_CACHE_KEY );

########
const PAGINATION_SETTINGS = [
   'onPageRecords'   => 12,  // количество товаров на странице
   'requestInterval' => 3,  // количество ссылок вокруг центральной 
   'startPaging'     => 2, // количество страниц с которых начинается paging
   'template'        => '/pagination/base',
];

########
// данные для создания продукта в EditorController
const ARR_CATEGORY_ID = [
   1 => 'makeup',
   2 => 'for-face',
   3 => 'for-oral-cavity',
   4 => 'for-hair',
   5 => 'for-body',
   6 => 'for-hands',
   7 => 'for-feet',
   8 => 'aromatherapy',
   9 => 'gift-set',
   10 =>'accessories',
];

const ARR_CATEGORY_SLUG = [
   'декоративная косметика' => 'makeup',
   'для лица'               => 'for-face',
   'для полости рта'        => 'for-oral-cavity',
   'для волос'              => 'for-hair',
   'для тела'               => 'for-body',
   'для рук'                => 'for-hands',
   'для ног'                => 'for-feet',
   'ароматерапия'           => 'aromatherapy',
   'подарочные наборы'      => 'gift-set',
   'аксессуары'             => 'accessories',
];

########
