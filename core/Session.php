<?php
namespace Master;

class Session
{

   private function generateCsrfToken()
   {
      if (! isset($_SESSION['csrf_token'])) {
         $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      
      }
   
   }
   
   public function __construct()
   {
      //session_save_path(realpath(dirname($_SERVER['DOCUMENT_ROOT']) .'/Master/session/'));
      session_set_cookie_params( [ 
         'lifetime' => 0, //31536000,     // год
         'path'     => '/',         // для всех путей в домене 
         'secure'   => true,       // заставляет браузер отправлять cookie только по HTTPS
         'httponly' => true,      // запрещает доступ к cookie из JavaScript через document.cookie.
         'samesite' => 'Strict', // 'Lax' будут ли cookies отправляться при межсайтовых запросах
      ] );
      session_start( [ 
         'name'                   => 'JapanInRu',
         'sid_length'             => 96,
         'sid_bits_per_character' => 6,
         'use_strict_mode'        => true, // является обязательным для общей безопасности сессии
      ] );
      /*
      date_default_timezone_set('Asia/Yekaterinburg'); //('Europe/Moscow');
      $_SESSION['date']                 = date(DATE_RSS);
      $_SESSION['remote_addr']          = $_SERVER['REMOTE_ADDR'];
      $_SESSION['http_user_agent']      = $_SERVER['HTTP_USER_AGENT'];
      */
      $this->generateCsrfToken();
      /*
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
      $_SESSION['info'] = 'created';
      */
   }

   // Проверяет наличие ключа в точечной нотации
   public function has(string $key): bool
   {
      $keys = explode('.', $key);
      $session_data = $_SESSION; // копия — не модифицирует $_SESSION

      foreach ($keys as $k) {
         if (isset($session_data[$k])) {
               $session_data = $session_data[$k];
         } else {
               return false;
         }
      }
      return true;
   }

   // Устанавливает значение по пути, создавая вложенные массивы при необходимости
   public function set(string $key, $value): void
   {
      $keys = explode('.', $key);
      // Используем ссылку, чтобы изменять непосредственно $_SESSION
      $ref =& $_SESSION;

      foreach ($keys as $k) {
         if (!isset($ref[$k]) || !is_array($ref[$k])) {
               // если ключ не существует или не массив — создаём массив,
               // кроме последнего уровня (последний будет перезаписан значением)
               $ref[$k] = [];
         }
         // Переходим внутрь по ссылке
         $ref =& $ref[$k];
      }

      // На этом уровне $ref — ссылка на конечный элемент, записываем значение
      $ref = $value;
   }

   // Альтернатива: безопасный get с дефолтом
   public function get(string $key, $default = null)
   {
      $keys = explode('.', $key);
      $session_data = $_SESSION;

      foreach ($keys as $k) {
         if (isset($session_data[$k])) {
               $session_data = $session_data[$k];
         } else {
               return $default;
         }
      }
      return $session_data;
   }


   public function remove($key): bool
   {
      if (isset($_SESSION[$key])) {
         unset($_SESSION[$key]);
         return true;
      
      }
      return false;
   
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
