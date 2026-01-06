<?php
namespace Master;

class Application
{   
   protected string $uri;

   public View             $view;
   public Abort            $abort;
   public Session          $session;
   public Request          $request;
   public Response         $response;
   public Router           $router;
   public Database         $db;
   public Cache            $cache;
   public Administrator    $admin;
   
   public static Application $app;

   public function __construct()
   {
      self::$app = $this;

      $this->uri = $_SERVER['REQUEST_URI'];
      $this->view     = new View();
      $this->abort    = new Abort();
      $this->session  = new Session();
      $this->request  = new Request($this->uri);
      $this->response = new Response();
      $this->router   = new Router($this->request, $this->response);
      $this->db       = new Database();
      $this->cache    = new Cache();
      $this->admin    = new Administrator();

   }

   public function run(){ print_r($this->router->dispatch()); }
   

}
