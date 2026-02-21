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

      $role = $_SESSION["role"];
                     
      if ($role == "admin" || $role == "assistant") {

         if (isset($_SESSION["pass"])) {
            
            $pass = app()->admin->pass();
            
            if (password_verify($pass, $_SESSION["pass"])) {

               $entry = bin2hex(random_bytes(32));
               define("PROTECTED_ACCESS", $entry);
               include_once "../entry/index.php";
            
            } else {
            
               echo  "<div style=\"text-align:center;background-color:red\"><br>
                        <h2>&emsp;Доступ запрещен.</h2><br>
                      </div>";

            }

         } else {
            
            echo  "<div style=\"text-align:center;background-color:red\"><br>
                     <h2>&emsp;Доступ запрещен.</h2><br>
                   </div>";
         
         }
      
      } else {

         echo  "<div style=\"text-align:center;background-color:red\"><br>
                  <h2>&emsp;Доступ запрещен.</h2><br>
                </div>";

      }

   }

}';

      if (array_intersect(array(Application::$app->session->get("email")), $administrator)) {

         Application::$app->session->set("role", "admin");
         if ($this->pass()) {

            session_regenerate_id(true);
            file_put_contents($filename, $content);

         }

      } else if (array_intersect(array(Application::$app->session->get("email")), ADMIN_A)) {

         Application::$app->session->set("role", "assistant");
         if ($this->pass()) {

            session_regenerate_id(true);
            file_put_contents($filename, $content);

         }

      } else {

         Application::$app->response->redirect("/");

      }
   
   }

}
