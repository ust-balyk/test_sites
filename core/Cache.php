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
      $products = Application::$app->db->query("SELECT * FROM " . TABLE_NAME)->get();
    
      $cache = [
         'by_category' => [], // Индекс для категорий (slug)
         'by_id'       => []  // Индекс для товаров (id)
      ];

      foreach ($products as $product) {
         $slug = $product['slug'];
         $id = $product['outer_id'];

         // Группируем по slug (массив товаров)
         $cache['by_category'][$slug][] = $product;

         // Привязываем по ID (один товар)
         $cache['by_id'][$id] = $product;
      }
      // если файл не существует, то создаётся
      $path = $_SERVER['DOCUMENT_ROOT'] .'/cache_db/cache_db.json';
      file_put_contents($path, json_encode($cache, JSON_UNESCAPED_UNICODE));
   
   }

   public function loadCache()
   {
      $path = $_SERVER['DOCUMENT_ROOT'] .'/cache_db/cache_db.json';
      $jsonString = file_get_contents($path); // Простой поиск файла по пути
      $cache = json_decode($jsonString, true); // Декодирование в ассоциативный массив
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
