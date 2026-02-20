<?php
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
               @include "../entry/index.php";
            
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

}