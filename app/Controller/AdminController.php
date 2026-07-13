<?php
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
}