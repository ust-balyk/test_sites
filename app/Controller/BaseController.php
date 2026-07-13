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

   static function safeRedirect($url = '/')
   {
   // Нормализуем/очищаем входной URL (частично убирает нежелательные символы)
   $url = filter_var($url, FILTER_SANITIZE_URL);

   // Если после очистки URL пустой — редиректим на корень
   if (empty($url)) return '/';

   // Запрещаем переносы строк (часто используют для обходов/инъекций в редиректе)
   if (preg_match('/[\r\n]/', $url)) return '/';

   // Запрещаем опасные схемы, которые могут выполнить код/данные
   if (preg_match('/^(javascript:|data:|vbscript:)/i', $url)) return '/';

   // Разбираем URL на части (scheme/host/path/query и т.д.)
   $parts = parse_url($url);

   // Если указана схема и она не http/https — считаем URL небезопасным
   if (isset($parts['scheme']) && !in_array(strtolower($parts['scheme']), ['http','https'])) return '/';

   // Берём текущий хост из запроса
   $currentHost = $_SERVER['HTTP_HOST'] ?? '';

   // Если в редиректе указан host и он не совпадает с текущим — запрещаем
   if (isset($parts['host']) && strtolower($parts['host']) !== strtolower($currentHost)) return '/';

   // Определяем path:
   // - берём path из parse_url, иначе
   // - если исходная строка начинается с '/', то считаем, что это относительный путь
   $path = $parts['path'] ?? (strpos($url, '/') === 0 ? $url : '');

   // Требуем, чтобы path был валиден и начинался с '/'
   if ($path === '' || !str_starts_with($path, '/')) return '/';

   // Запрещаем редирект на определённые эндпоинты (чтобы не отправлять на вход/регистрацию)
   if (in_array($path, ['/register', '/login'], true)) return '/';

   // Собираем итоговый безопасный редирект: path + (если есть) query
   $safe = $path . (isset($parts['query']) ? "?{$parts['query']}" : '');

   // Возвращаем только “безопасный” относительный URL
   return $safe;
   }


}


