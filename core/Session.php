<?php
namespace Master;

class Session
{
   public function __construct()
   {
      session_save_path(realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/Master/session')); 
      session_start([
         'cookie_lifetime' => 31536000, // год
      ]);
      date_default_timezone_set('Asia/Yekaterinburg'); //('Europe/Moscow');
      $_SESSION['start'] = date(DATE_RSS);
      $this->generateCsrfToken();
      
   }
   
   public function generateCsrfToken()
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
