<?php
namespace Master;

class Application
{   
   protected string          $uri;
   public Session            $session;
   public Request            $request;
   public Response           $response;
   public Router             $router;
   public Abort              $abort;
   public Database           $db;
   public Cache              $cache;
   public View               $view;
   public Administrator      $admin;
   
   public static Application $app;

   public function __construct()
   {
      self::$app = $this;

      $this->uri      = $_SERVER['REQUEST_URI'];
      $this->session  = new Session();
      $this->request  = new Request($this->uri);
      $this->response = new Response();
      $this->router   = new Router($this->request, $this->response);
      $this->abort    = new Abort();
      $this->db       = new Database();
      $this->view     = new View();
      $this->cache    = new Cache();
      $this->admin    = new Administrator();

   }

   public function run(){ print_r($this->router->dispatch()); }
   

}
