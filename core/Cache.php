<?php
namespace Master;
use App\Helper\Text\Text;


class Cache
{
   private $indexed_DB = [];
   private $path = './../cache/db/cache_db.json';

   public function __construct()
   {

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

      foreach ($products as &$product) {
         // Чистим описание прямо в исходном объекте/массиве
         $product['title'] = Text::clean($product['title']);
         $product['description'] = Text::clean($product['description']);

         // Распределяем уже чистые данные
         $cache['by_category'][$product['slug']][] = $product;
         $cache['by_id'][$product['outer_id']] = $product;
      
      }
      unset($product); // Обязательно удаляем ссылку после цикла!


      // Сохраняем на диск
      file_put_contents($this->path, json_encode($cache, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);
      
      // Обязательно обновляем данные в памяти текущего объекта
      $this->indexed_DB = $cache;    
   
   }

   // Теперь данные доступны через этот метод или напрямую
   public function getCache_db()
   {
      return $this->indexed_DB;
   
   }
   
   public function setCache_menu($key, $data, $seconds = 3600): void
   {      
      if (! file_exists(CACHE_MENU)) {
         mkdir(CACHE_MENU);
         
      }
      $content[$key] = $data;
      $content['time'] = time() + $seconds;
      $cache_file = CACHE_MENU . '/' . md5($key) . '.txt';
      
      file_put_contents($cache_file, serialize($content));
   
   }

   public function getCache_menu($key, $default = null)
   {
      $cache_file = CACHE_MENU . '/' . md5($key) . '.txt';
      
      if (file_exists($cache_file)) {
         $content = unserialize(file_get_contents($cache_file));
         
         if (isset($content['time']) && time() <= $content['time']) {
            return $content[$key];
         
         }
         //unlink($cache_file);
         file_put_contents($cache_file, "");
      
      }
      return $default;
   
   }

   public function removeCache_menu($key): void
   {
      $cache_file = CACHE_MENU . '/' . md5($key) . '.txt';
      
      if (file_exists($cache_file)) {
         //unlink($cache_file);
         file_put_contents($cache_file, "");
      
      }
      
   }

   public function set($key, $data, $seconds = 3600): void
   {      
      if (! file_exists(CACHE)) {
         mkdir(CACHE);
         
      }
      $content[$key] = $data;
      $content['time'] = time() + $seconds;
      $cache_file = CACHE . '/' . md5($key) . '.txt';
      
      file_put_contents($cache_file, serialize($content));
   
   }
   /*
   public function set($key, $data, $seconds = 3600): void
   {
      // 1. Проверяем и создаём директорию для кеша (если её нет)
      if (!file_exists(CACHE)) {
         if (!mkdir(CACHE, 0755, true)) { // 0755 = права доступа (чтение/запись для владельца)
            throw new RuntimeException("Не удалось создать директорию для кеша: " . CACHE);
         }
      }
   
      // 2. Проверяем, что директория доступна для записи
      if (!is_writable(CACHE)) {
         throw new RuntimeException("Директория кеша недоступна для записи: " . CACHE);
      }
   
      // 3. Формируем имя файла
      $cache_file = CACHE . '/' . md5($key) . '.txt';
   
      // 4. Проверяем, что данные можно сериализовать
      if (! $this->is_serializable($data)) {
         throw new InvalidArgumentException("Данные не могут быть сериализованы.");
      }
   
      // 5. Записываем данные атомарно (сначала во временный файл, затем переименовываем)
      $temp_file = tempnam(CACHE, 'tmp_');
      if ($temp_file === false) {
         throw new RuntimeException("Не удалось создать временный файл для кеша.");
      }
   
      $content = [
         $key => $data,
         'time' => time() + $seconds,
      ];
   
      $result = file_put_contents($temp_file, serialize($content));
      if ($result === false) {
         unlink($temp_file); // Удаляем временный файл, если запись не удалась
         throw new RuntimeException("Не удалось записать данные в кеш.");
      }
   
      // Атомарное переименование (гарантирует, что файл не будет повреждён)
      if (!rename($temp_file, $cache_file)) {
         unlink($temp_file); // Удаляем временный файл, если переименование не удалось
         throw new RuntimeException("Не удалось сохранить кеш.");
      }
   }*/
   /*
   public function set($key, $data, $seconds = 3600): void
   {
      // 1. Проверяем и создаём директорию для кеша (если её нет)
      if (!file_exists(CACHE)) {
         if (!mkdir(CACHE, 0755, true)) {
               throw new RuntimeException("Не удалось создать директорию для кеша: " . CACHE);
         }
      }

      // 2. Проверяем, что директория доступна для записи
      if (!is_writable(CACHE)) {
         throw new RuntimeException("Директория кеша недоступна для записи: " . CACHE);
      }

      // 3. Формируем имя файла
      $sanitizedKey = preg_replace('/[^a-zA-Z0-9_]/', '_', $key);
      $cache_file = CACHE . '/' . md5($sanitizedKey) . '.txt';

      // 4. Проверяем, что данные можно сериализовать
      if (!is_serializable($data)) {
         throw new InvalidArgumentException("Данные не могут быть сериализованы.");
      }

      // 5. Записываем данные атомарно
      $temp_file = tempnam(CACHE, 'tmp_');
      if ($temp_file === false) {
         throw new RuntimeException("Не удалось создать временный файл для кеша.");
      }

      $content = [
         $key => $data,
         'time' => time() + $seconds,
      ];

      $result = file_put_contents($temp_file, serialize($content));
      if ($result === false) {
         unlink($temp_file);
         throw new RuntimeException("Не удалось записать данные в кеш.");
      }

      // Атомарное переименование
      if (!rename($temp_file, $cache_file)) {
         unlink($temp_file);
         throw new RuntimeException("Не удалось сохранить кеш.");
      }
   }*/



   public function is_serializable($data): bool
   {
      try {
         serialize($data);
         return true;
      } catch (Exception $e) {
         return false;
      }
   }

   //  для очистки устаревшего кеша
   public function cleanup(): void
   {
      $files = glob(CACHE . '/*.txt');
      $current_time = time();
   
      foreach ($files as $file) {
         $content = unserialize(file_get_contents($file));
         if (isset($content['time']) && $content['time'] < $current_time) {
            unlink($file);
         }
      }
   }

   public function get($key)
   {
      $cache_file = CACHE . '/' . md5($key) . '.txt';
      if (!file_exists($cache_file)) {
         return null;
      }
   
      $content = unserialize(file_get_contents($cache_file));
      if (!isset($content[$key]) || !isset($content['time']) || $content['time'] < time()) {
         return null; // Кеш устарел или ключ не найден
      }
   
      return $content[$key];
   }
   

}
