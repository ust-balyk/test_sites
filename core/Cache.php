<?php
namespace Master;

class Cache
{
   private $indexed_DB = [];
   private $path;

   public function __construct()
   {
      $this->path = __DIR__ . '/../cache_db/cache_db.json';

      if (file_exists($this->path)) {
         // Если файл есть, просто загружаем его в память
         $this->indexed_DB = json_decode(file_get_contents($this->path), true);
      } else {
         // Если файла нет, запускаем полное обновление
         $this->refreshCache();
      }
   }

   public function refreshCache()
   {
      $products = Application::$app->db->query("SELECT * FROM " . TABLE_NAME)->get();    
      
      $cache = [
         'by_category' => [],
         'by_id'       => []
      ];

      foreach ($products as $product) {
         $cache['by_category'][$product['slug']][] = $product;
         $cache['by_id'][$product['outer_id']] = $product;
      }

      // Сохраняем на диск
      file_put_contents($this->path, json_encode($cache, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);
      
      // Обязательно обновляем данные в памяти текущего объекта
      $this->indexed_DB = $cache;    
   }

   // Теперь данные доступны через этот метод или напрямую
   public function getCache()
   {
      return $this->indexed_DB;
   
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
