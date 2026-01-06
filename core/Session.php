<?php
namespace Master;

class Session
{
   public function __construct()
   {
      session_start();
      /*([
         'cookie_lifetime' => 86400, // 24 часа
      ]);*/
      
      $this->generateCsrfToken();
      
   }
   
   private function generateCsrfToken()
   {
      if (! isset($_SESSION['csrf_token'])) {
         $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      
      }
   }
   
   public function set($key, $value)
   {
      if ($value != false) {
         $_SESSION[$key] = $value;
         return true;

      }
      return false;

   }

   public function get($key, $value=false)
   {
      return $_SESSION[$key] ?? $value;

   }

   public function isKey($key)
   {
      if (isset($_SESSION[$key])) {
         return true;

      }
   }  

   public function remove($key): void
   {
      if (isset($_SESSION[$key])) {
         unset($_SESSION[$key]);
      
      }
   }

   public function setFlash($key, $value): void
   {
      $_SESSION['flash'][$key] = $value;

   }

   public function getFlash($key, $value_default=null)
   {
      if (isset($_SESSION['flash'][$key])) {
         $value = $_SESSION['flash'][$key];
         unset($_SESSION['flash'][$key]);

      }
      return $value ?? $value_default;

   }

}
