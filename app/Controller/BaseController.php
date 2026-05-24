<?php
namespace App\Controller;

abstract class BaseController
{

   public static function init() {
        
      (new static)->recordBackUrl(); // Создаем временный объект для записи URL
   
   }

   
   private function recordBackUrl() 
   {
      // Игнорируем POST-запросы (чтобы не сохранять URL после отправки форм)
      if ($_SERVER['REQUEST_METHOD'] !== 'GET') return;

      $uri = $_SERVER['REQUEST_URI'];

      // Исключаем системные страницы (улучшенная проверка)
      $excluded = ['/register', '/login'];
      foreach ($excluded as $page) {
         if (strpos($uri, $page) !== false) return;
      
      }

      // Инициализируем переменные, если их нет
      if (!isset($_SESSION['target_page'])) {
         $_SESSION['target_page'] = $uri;
         $_SESSION['back_url'] = '/';
         return;
      
      }

      // Если мы перешли на login/register -> 'target_page' для вернуться назад
      if ($_SESSION['target_page'] !== $uri) {
         // Сначала сохраняем то, где были, как "назад"
         $_SESSION['back_url'] = $_SESSION['target_page'];
         // Затем обновляем текущий адрес
         $_SESSION['target_page'] = $uri;
      
      }
   
   }

   /*
   static function safeRedirect($url = '/') 
   {
      $url = filter_var($url, FILTER_SANITIZE_URL);
      if (empty($url)) return '/';

      // Защита от CRLF/инъекций заголовков
      if (preg_match('/[\r\n]/', $url)) return '/';

      // Базовая проверка на XSS
      if (preg_match('/^(javascript:|data:|vbscript:)/i', $url)) return '/';

      $parts = parse_url($url);
      $currentHost = $_SERVER['HTTP_HOST'] ?? '';

      // Защита от внешних редиректов
      if (isset($parts['host']) && strtolower($parts['host']) !== strtolower($currentHost)) {
         return '/';
      }

      // Защита от зацикливания на формах
      $path = $parts['path'] ?? '';
      if (in_array($path, ['/register', '/login'])) return '/';

      return $url;

   }*/

   static function safeRedirect($url = '/')
   {
      $url = filter_var($url, FILTER_SANITIZE_URL);
      if (empty($url)) return '/';
      if (preg_match('/[\r\n]/', $url)) return '/';
      if (preg_match('/^(javascript:|data:|vbscript:)/i', $url)) return '/';

      $parts = parse_url($url);

      if (isset($parts['scheme']) && !in_array(strtolower($parts['scheme']), ['http','https'])) return '/';

      $currentHost = $_SERVER['HTTP_HOST'] ?? '';
      if (isset($parts['host']) && strtolower($parts['host']) !== strtolower($currentHost)) return '/';

      $path = $parts['path'] ?? (strpos($url, '/') === 0 ? $url : '');
      if ($path === '' || !str_starts_with($path, '/')) return '/';
      if (in_array($path, ['/register', '/login'], true)) return '/';

      $safe = $path . (isset($parts['query']) ? "?{$parts['query']}" : '');
      return $safe;
   }


}


