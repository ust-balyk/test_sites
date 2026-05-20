<?php
namespace Master;

final class Administrator
{  
   public function pass()
   {
      static $pass = bin2hex(random_bytes(32));       
      $_SESSION['pass'] = password_hash($pass, PASSWORD_DEFAULT);

      return $pass;

   }
   
   final function lock()
   {
      $administrator = [

         "ust.balyk@gmail.com"

      ];

      $filename = '../app/Controller/AdminController.php';

      $content = '<?php
namespace App\Controller;
                  
class AdminController
{
   static function index() {

      if (isset($_SESSION["user"]["role"])) {          
         $role = $_SESSION["user"]["role"];
                     
         if ($role === "master" || $role === "assistant") {

            if (isset($_SESSION["pass"])) {            
               $pass = app()->admin->pass();
            
               if (password_verify($pass, $_SESSION["pass"])) {
                  $entry = bin2hex(random_bytes(32));
                  define("PROTECTED_ACCESS", $entry);
                  include_once "../entry/index.php";
            
               } else {
                  app()->response->redirect("/");

               }
            } else {
               app()->response->redirect("/");
         
            }
         } else {
            app()->response->redirect("/");

         }
      } else {
         app()->response->redirect("/");
      
      }
   }
}';

      if (array_intersect(array(Application::$app->session->get("user.email")), $administrator) &&
         (Application::$app->session->get("user.role") === "master") && ($this->pass())) 
      {
         session_regenerate_id(true);
         file_put_contents($filename, $content);

      } else if (array_intersect(array(Application::$app->session->get("user.email")), ADMIN_A) &&
         (Application::$app->session->get("user.role") === "assistant") && ($this->pass())) 
      {
         session_regenerate_id(true);
         file_put_contents($filename, $content);

      } else { Application::$app->response->redirect("/"); }
 
   }

}
