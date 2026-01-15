<?php
namespace Master;

class Session
{

   private function generateCsrfToken($length = 32)
   {
      if (! isset($_SESSION['csrf_token'])) {
         $_SESSION['csrf_token'] = bin2hex(random_bytes($length));
      
      }
   
   }
   
   public function __construct()
   {
      session_save_path(realpath(dirname($_SERVER['DOCUMENT_ROOT']) .'/Master/session/'));
      session_set_cookie_params( [ 
         'lifetime' => 0,
         'secure' => true, // заставляет браузер отправлять cookie только по HTTPS
         'httponly' => true, // запрещает доступ к cookie из JavaScript через document.cookie.
         'samesite' => 'Lax', //'Strict' будут ли cookies отправляться при межсайтовых запросах
      ] );
      session_start( [ 
         'name' => 'JapanInRu',
         'sid_length' => 96,
         'sid_bits_per_character' => 6,
         'use_strict_mode' => true, //  является обязательным для общей безопасности сессии
      ] );
      
      date_default_timezone_set('Asia/Yekaterinburg'); //('Europe/Moscow');
      $_SESSION['DATE']                 = date(DATE_RSS);
      $_SESSION['REMOTE_ADDR']          = $_SERVER['REMOTE_ADDR'];
      $_SESSION['HTTP_USER_AGENT']      = $_SERVER['HTTP_USER_AGENT'];

      $this->generateCsrfToken();

      $data = [
         date(DATE_RSS),
         $_SERVER['REMOTE_ADDR'],
         $_SERVER['HTTP_ACCEPT_LANGUAGE'],
         $_SERVER['HTTP_USER_AGENT'],
         $_SERVER['REQUEST_URI'], 
      ];
      if (!isset($_SESSION['info'])) {
         $info = implode("\n", $data);
         file_put_contents('../log/enter.txt', $info . "\n
--------------------------------------------------------\n", FILE_APPEND|LOCK_EX);
      
      }
      $_SESSION['info'] = 'stop';

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
