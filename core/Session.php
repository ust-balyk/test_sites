<?php
namespace Master;

class Session
{
   private string $session_path;
   private string $log_dir;

   public function __construct(string $session_path = null, string $log_dir = null)
   {
      $this->session_path = $session_path ?? dirname($_SERVER['DOCUMENT_ROOT']) . '/Master/session/tmp';
      $this->log_dir = rtrim($log_dir ?? 
         (__DIR__ . '/../session/log_visit/'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

      // Создаём папку для сессий
      if (!is_dir($this->session_path) && !mkdir($this->session_path, 0755, true) && !is_dir($this->session_path)) {
         throw new \RuntimeException('Cannot create session directory: ' . $this->session_path);
      }
      session_save_path($this->session_path);

      // Старт сессии если не запущена
      if (session_status() !== PHP_SESSION_ACTIVE) {
         session_start([
               'name'                   => '23~',
               'sid_length'             => 96,
               'sid_bits_per_character' => 6,
               'use_strict_mode'        => true,
               'cookie_lifetime'        => 0,
               'cookie_path'            => '/',
               'cookie_httponly'        => true,
         ]);
      }

      date_default_timezone_set('Asia/Yekaterinburg');

      $this->generateCsrfToken();

      if (!isset($_SESSION['info'])) {
         $this->logFirstVisit();
         $_SESSION['info'] = 'visit';
      }
   }


   private function generateCsrfToken(): void
   {
      if (empty($_SESSION['csrf_token'])) {
         $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }
   }


   private function logFirstVisit(): void
   {
      $ip   = $this->getClientIp();
      $user = $this->safeString($_SERVER['HTTP_USER_AGENT'] ?? '', 512);
      $lang = $this->safeString($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '', 64);
      $uri  = $this->safeString($_SERVER['REQUEST_URI'] ?? '', 1024);
      //$date = date('c');
      $date = date(DATE_RSS);

      // Создаём папку для логов
      if (!is_dir($this->log_dir) && !mkdir($this->log_dir, 0755, true) && !is_dir($this->log_dir)) {
         error_log('Cannot create log directory: ' . $this->log_dir);
         return;
      }

      $entry = sprintf(
         "[%s] IP=%s; LANG=%s; USER=%s; URI=%s\n--------------------------------------------------------\n",
         $date, $ip, $lang, $user, $uri
      );

      $file = $this->log_dir . 'enter.txt';
      if (false === @file_put_contents($file, $entry, FILE_APPEND | LOCK_EX)) {
         error_log('Failed to write to log file: ' . $file);
      }

      // Сохраняем в сессии (пример)
      $_SESSION['date'] = $date;
      $_SESSION['remote_addr'] = $ip;
      $_SESSION['http_user_agent'] = $user;
   }

   private function getClientIp(): string
   {
      $keys = ['HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
      foreach ($keys as $key) {
         if (!empty($_SERVER[$key])) {
               $ips = explode(',', (string)$_SERVER[$key]);
               $ip = trim($ips[0]);
               if (filter_var($ip, FILTER_VALIDATE_IP)) {
                  return $ip;
               }
         }
      }
      return '0.0.0.0';
   }

   private function safeString(string $s, int $max = 1024): string
   {
      $s = trim($s);
      if ($s === '') return '';
      $s = mb_substr($s, 0, $max);
      return preg_replace('/[[:cntrl:]]+/u', ' ', $s);
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
      $session =& $_SESSION;

      foreach ($keys as $k) {
         if (!isset($session[$k]) || !is_array($session[$k])) {
               // если ключ не существует или не массив — создаём массив,
               // кроме последнего уровня (последний будет перезаписан значением)
               $session[$k] = [];
         }
         // Переходим внутрь по ссылке
         $session =& $session[$k];
      }

      // На этом уровне $session — ссылка на конечный элемент, записываем значение
      $session = $value;
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


   public function remove(string $key): void
   {
      $keys = explode('.', $key);
      $session =& $_SESSION;

      // Проходим по всем ключам, кроме последнего
      for ($i = 0; $i < count($keys) - 1; $i++) {
         $k = $keys[$i];
         if (!isset($session[$k]) || !is_array($session[$k])) {
            return; // Путь не существует
         
         }
         $session =& $session[$k];
      }

      // Удаляем последний ключ
      unset($session[$keys[$i]]);
   
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
