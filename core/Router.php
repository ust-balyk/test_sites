<?php
namespace Master;

class Router
{   
   protected Request $request;
   protected Response $response;
   protected array $routes = [];
   protected array $route_params = [];

   public function __construct(Request $request, Response $response)
   {  
      $this->request = $request;
      $this->response = $response;

   }

   public function add($path, $callback, $method): self
   {  
      $path = strip_tags(trim($path, '/'));
      
      if (is_array($method)) {
         $method = array_map('strtoupper', $method);
      
      } else {
         $method = [strtoupper($method)];

      }
      $this->routes[] = [
          
         'path'            => "/$path",
         'callback'        => $callback,
         'method'          => $method,
         'need_csrf_token' => true,
         'closed_for'      => [],

      
      ];
      
      return $this;

   }

   public function get($path, $callback): self
   {  
      return $this->add($path, $callback, 'GET');

   }

   public function post($path, $callback): self
   {  
      return $this->add($path, $callback, 'POST');

   }

   public function getRoutes(): array
   {  
      return $this->routes;

   }

   protected function matchRoute($path): mixed
   {  
      foreach ($this->routes as $route) {

         if (@preg_match("#^{$route['path']}$#i", "/{$path}", $matches)
            && in_array($this->request->getMethod(), $route['method'])) {
            
            if ($route['closed_for']) {
               foreach ($route['closed_for'] as $x) {
                  $closed = CLOSED_FOR[$x] ?? false;
                  if ($closed) {
                     (new $closed)->lock();
                  
                  }
               }
            }
            
            if (Application::$app->request->isPost()) {
               if ($route['need_csrf_token'] && !$this->validateCsrfToken()) {
                  if (Application::$app->request->isAjax()) {
                     echo json_encode(['status' => 'error',
                                       'data' => 'Security error']);
                     die;
                  } else {
                     //session()->setFlash('error', 'ошибка безопастности.');
                     //response()->redirect();
                     Application::$app->abort->error(419);
                  }
               }
            }  
            
            foreach ($matches as $k => $v) { # поиск <slug>
               if (is_string($k)) {
                  $this->route_params[$k] = $v;
               }
            }   
            return $route;
         }
      }      
      return false;
   }

   public function dispatch(): mixed
   {  
      $path = $this->request->getPath();
      $route = $this->matchRoute($path);
      
      if ($route === false) {
         Application::$app->abort->error();
         //Application::$app->response->redirect('/');
      
      } elseif (is_array($route['callback'])
         && array_key_exists('1', $route['callback'])) {
         
         $route['callback'][0] = new $route['callback'][0];
         return call_user_func($route['callback']);
      
      } elseif (is_callable($route['callback'])) {
         return call_user_func($route['callback']);
      
      } else {
         $class = array_shift($route['callback']);
         return call_user_func($class . '::index');
      
      }
   }
   
   public function withoutCsrfToken(): self
   {
      $this->routes[array_key_last($this->routes)]['need_csrf_token'] = false;
      return $this;
      
   }
   
   public function validateCsrfToken(): bool
   {
      return Application::$app->request->post('csrf_token') &&
         (Application::$app->request->post('csrf_token') ==
      Application::$app->session->get('csrf_token'));
   
   }

   public function closed_for($person): self
   {
      $this->routes[array_key_last($this->routes)]['closed_for'] = $person;
      return $this;

   }

}
