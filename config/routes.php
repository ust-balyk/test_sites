<?php
/*
доступен маршрут без указания метода
класс должен иметь единственный статический метод index()
$app->router->get('/', [App\Controller\PageController::class], 'index');
равно что -> $app->router->get('/', [App\Controller\PageController::class]);
*/
use \App\Controller\HomeController;
use \App\Controller\CategoryController;
use \App\Controller\ProductController;
use \App\Controller\PageController;
use \App\Controller\CartController;
use \App\Controller\UserController;
use \App\Controller\AccountController;
use \App\Controller\AdminController;

//$app->router->add('/', [HomeController::class, 'index'], ['POST', 'GET']);
$app->router->post('/', [HomeController::class])->withoutCsrfToken();
$app->router->get('/', [HomeController::class]);
$app->router->get('/cosmetics', [PageController::class, 'cosmetics']);
$app->router->get('/cosmetics/discount', [PageController::class, 'discount']);
$app->router->get('/cosmetics/([a-zA-Z-]+)', [CategoryController::class]);
$app->router->get('/cosmetics/([a-zA-Z-]+)/product/([0-9]+)', [ProductController::class]);
$app->router->get('/product/delivery', [PageController::class, 'delivery']);
$app->router->get('/add-to-cart([?id=0-9]+)', [CartController::class, 'add_to_cart']);
$app->router->get('/add-to-favorites([?id=0-9]+)', [CartController::class, 'add_to_favorites']);

$app->router->get('/register', [UserController::class, 'register'])->closed_for(['frend']);
$app->router->post('/register', [UserController::class, 'record'])->withoutCsrfToken();
$app->router->get('/login', [UserController::class, 'login'])->closed_for(['frend']);
$app->router->post('/login', [UserController::class, 'enter'])->withoutCsrfToken();
$app->router->get('/logout', [UserController::class, 'logout']);

$app->router->get('/account', [AccountController::class])->closed_for(['guest']);

$app->router->get('/home', [AdminController::class])->closed_for(['all']);

$app->router->get('/post/?(?P<slug>[a-z0-9-]+)/?', function() {
   return "<br>Post " . get_route_param("slug", "test");
});

//dump(__FILE__ . " str.: " . __LINE__, $app->router->getRoutes());
$app->router->get('/autor', function() { return
   '<pre><br><h3 style="text-indent:40px;color:grey">&copy; исламов вадим ханифович</h3></pre>'; });
