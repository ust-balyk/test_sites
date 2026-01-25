<?php
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
      
      } else {

         echo '<div style="text-align:center;background-color:red"><br>
                  <h2>&emsp;Доступ запрещен.</h2><br>
                </div>';

      }

   }

}