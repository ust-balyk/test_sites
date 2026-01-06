<?php
/*
доступен маршрут без указания метода
класс должен иметь единственный статический метод index()
$app->router->get('/', [App\Controller\PageController::class], 'index');
$app->router->get('/', [App\Controller\PageController::class]);
*/
use \App\Controller\HomeController;
use \App\Controller\PageController;
use \App\Controller\UserController;
use \App\Controller\AccountController;
use \App\Controller\AdminController;

//$app->router->add('/', [HomeController::class, 'index'], ['POST', 'GET']);
$app->router->post('/', [HomeController::class])->withoutCsrfToken();
$app->router->get('/', [HomeController::class]);

$app->router->get('/home', [AdminController::class])->closed_for(['all']);
$app->router->get('/category', [PageController::class]);

$app->router->get('/register', [UserController::class, 'register'])->closed_for(['friend']);
$app->router->post('/register', [UserController::class, 'record']);

$app->router->get('/login', [UserController::class, 'login'])->closed_for(['friend']);
$app->router->post('/login', [UserController::class, 'enter']);

$app->router->get('/account', [AccountController::class])->closed_for(['guest']);

$app->router->get('/logout', [UserController::class, 'logout']);

//$app->router->get('/home', [AdminController::class])->closed_for(['all']); // очередность? 

$app->router->get('/post/?(?P<slug>[a-z0-9-]+)/?', function() {
   return "<br>Post " . get_route_param("slug", "test");
});

//dump(__FILE__ . " str.: " . __LINE__, $app->router->getRoutes());
