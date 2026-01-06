<?php
namespace Master;

final class Administrator
{  
   public function pass()
   {
      $pass = $_SESSION['pass'] = password_hash (

         bin2hex(random_bytes(32)), PASSWORD_DEFAULT
      
      );
      return $pass;

   }
   
   final function lock()
   {
      $administrator = [
         "ust.balyk@mail.ru",
         "ust.balyk@gmail.com",
         "vadim.islamov@gmail.com",
      ];

      $filename = '../app/Controller/AdminController.php';

      $content = '<?php
namespace App\Controller;
use Master\Administrator;
                  
class AdminController
{
   static function index() {

      $role = $_SESSION["role"];
      $pass = app()->admin->pass();
                     
      if ($role == "admin" || $role == "assistant") {

         if ($_SESSION["pass"] === $pass) {

            $entry = password_hash($pass, PASSWORD_DEFAULT);
            define("PROTECTED_ACCESS", $entry);
            @include "../entry/index.php";

         }
      
      }
      echo \'<div style="text-align:center;background-color:red"><br>
               <h2>&emsp;Доступ запрещен.</h2><br>
            </div>\';
   }

}';

      if (array_intersect(array(Application::$app->session->get("email")), $administrator)) {

         Application::$app->session->set("role", "admin") &&
            Application::$app->session->set("pass", $this->pass());
         file_put_contents($filename, $content);

      } else if (array_intersect(array(Application::$app->session->get("email")), ADMIN_A)) {

         Application::$app->session->set("role", "assistant") &&
            Application::$app->session->set("pass", $this->pass());
         file_put_contents($filename, $content);

      } else {

         Application::$app->response->redirect("/");

      }
   }

}
