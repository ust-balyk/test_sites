<?php
namespace Master;

class Cache
{
   private $indexed_DB = [];

   public function __construct()
   {
      $cache_dir = $_SERVER['DOCUMENT_ROOT'] .'/cache_db';
      $path = $cache_dir . '/cache_db.json';

      // 1. Проверяем: существует ли файл и совпадает ли дата его создания с сегодняшней
      if (file_exists($path) && date('Y-m-d', filemtime($path)) === date('Y-m-d')) {
         $this->indexed_DB = json_decode(file_get_contents($path), true);
      } else {
         // 2. Если файл устарел или его нет — вызываем обновление
         $this->refreshCache();
      }
   
   }

   /**
    * Метод для принудительного обновления кэша
    * Можно вызывать из админки: cache()->refreshCache();
    */
   public function refreshCache()
   {
      $data = Application::$app->db->query("SELECT * FROM " . TABLE_NAME)->get();
    
      $cache = [
         'by_category' => [], // Индекс для категорий (slug)
         'by_id'       => []  // Индекс для товаров (id)
      ];

      foreach ($data as $product) {
         $slug = $product['slug'];
         $id = $product['id'];

         // Группируем по slug (массив товаров)
         $cache['by_category'][$slug][] = $product;

         // Привязываем по ID (один товар)
         $cache['by_id'][$id] = $product;
      }
      // если файл не существует, то создаётся
      $path = $_SERVER['DOCUMENT_ROOT'] . '/cache_db/cache_db.json';
      file_put_contents($path, json_encode($cache, JSON_UNESCAPED_UNICODE));
   
   }

   public function loadCache()
   {
      $path = $_SERVER['DOCUMENT_ROOT'] . '/cache_db/cache_db.json';

      if (!file_exists($path)) {
         error_log("[Cache] Файл не найден: $path");
         return ['by_category' => [], 'by_id' => []];
      }

      $cache = json_decode(file_get_contents($path, true));

      if (json_last_error() !== JSON_ERROR_NONE || !is_array($cache)) {
         error_log("[Cache] Ошибка JSON: " . json_last_error_msg());
           
         // Попытка самовосстановления
         $this->refreshCache();
           
         // Повторная попытка прочитать свежий файл
         $raw = file_get_contents($path);
         return json_decode($raw, true) ?? ['by_category' => [], 'by_id' => []];
      }
      return $cache;

   }


   public function set($key, $data, $seconds = 3600): void
   {
      if (! file_exists(CACHE_MENU)) {
         mkdir(CACHE_MENU);
      }
      $content[$key] = $data;
      $content['time'] = time() + $seconds;
      $cache_file = CACHE_MENU . '/' . md5($key) . '.txt';
      
      file_put_contents($cache_file, serialize($content));
   
   }

   public function get($key, $default = null)
   {
      $cache_file = CACHE_MENU . '/' . md5($key) . '.txt';
      
      if (file_exists($cache_file)) {
         $content = unserialize(file_get_contents($cache_file));
         
         if (isset($content['time']) && time() <= $content['time']) {
            return $content[$key];
         
         }
         unlink($cache_file);
         //file_put_contents($cache_file, "");
      
      }
      return $default;
   
   }

   public function removeCache($key): void
   {
      $cache_file = CACHE_MENU . '/' . md5($key) . '.txt';
      
      if (file_exists($cache_file)) {
         unlink($cache_file);
         //file_put_contents($cache_file, "");
      
      }
   }

}
