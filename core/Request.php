<?php
namespace Master;

class Request
{
   public string $formatted_uri;  // отформатированая строка запроса

   public function __construct($uri)
   {   
      $sanitized_uri = preg_replace('~[^a-zA-Z0-9-/]+~i', '', $uri);
      $this->formatted_uri = strtolower(trim($sanitized_uri, '/'));
       
   }


   public function getMethod(): string
   {  
      return strtoupper($_SERVER['REQUEST_METHOD']);

   }


   public function isGet(): bool
   {  
      return $this->getMethod() == "GET";

   }


   public function isPost(): bool
   {  
      return $this->getMethod() == "POST";

   }
    
   
   public function isAjax(): bool
   {  
      return isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
         && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
   
   }


   public function get(string $key, $default_value = null): ?string
   {  
      return $_REQUEST[$key] ?? $default_value;

   }

   public function post($key, $default_value = null): ?string
   {  
      return $_POST[$key] ?? $default_value;

   }


   public function get_SLUG_or_ID()
   {
      $uri = $this->formatted_uri;
      // Инициализируем массив [slug, id] пустыми значениями
      $result = [null, null];

      $valid_keys = [
         'cosmetics',
         'product',
      ];

      $valid_values = [
         "makeup",
         "for-body",
         "for-face",
         "for-oral-cavity",
         "for-hair",
         "for-hands",
         "for-feet",
         "aromatherapy",
         "gift-set",
         "accessories"
      ];

      $segments = explode('/', trim($uri, '/'));
      $count = count($segments);

      if ($count === 2 || $count === 4) {
         for ($i = 0; $i < $count; $i += 2) {
            $key = $segments[$i];
            $value = $segments[$i + 1] ?? null;

            if (in_array($key, $valid_keys)) {

               if (ctype_digit($value)) {
                  // Записываем ID во второй индекс (1)
                  $result[1] = (int)$value;

               } else if (in_array($value, $valid_values)) {
                  // Записываем SLUG в первый индекс (0)
                  $result[0] = (string)$value;

               }
            }
         }
      }

      return $result;

   }

   /*  

   public function get_SLUG_or_ID(): array
   {
      $uri = $this->formatted_uri;
      $result = [null, null]; // [slug (value), id or null]

      $valid_keys = array_map('mb_strtolower', ['cosmetics', 'product']);
      $valid_values = array_map('mb_strtolower', [
         "makeup","for-body","for-face","for-oral-cavity","for-hair",
         "for-hands","for-feet","aromatherapy","gift-set","accessories"
      ]);

      $segments = array_values(array_filter(explode('/', trim($uri, '/')), fn($s) => $s !== ''));
      $segments = array_map('mb_strtolower', $segments);
      $count = count($segments);

       if ($count === 1) {
         // Один сегмент = значение (slug)
         $value = $segments[0];
         // Попытка точного соответствия значения среди valid_values
         if (in_array($value, $valid_values, true)) {
            $result[0] = $value;
            return $result;
         }
         // Иначе попробовать исправить опечатку среди valid_values
         $closest = $this->getClosestMatch($value, $valid_values);
         if ($closest !== null) {
            // Редирект на исправленный путь
            $correct_url = '/' . $closest;
            header("Location: $correct_url", true, 301);
            exit();
         }
         // Если значение не найдено в valid_values, можно также попробовать считать его ключом
         $closestKey = $this->getClosestMatch($value, $valid_keys);
         if ($closestKey !== null) {
            // считаем, что это категория без value — возвращаем slug = категория
            $result[0] = $closestKey;
            return $result;
         }
         return $result;
      }
   
      if ($count >= 2) {
         // Первый сегмент — ключ, второй — значение (игнорируем лишние сегменты)
         $key = $segments[0];
         $value = $segments[1];

         // Исправление/проверка ключа
         if (!in_array($key, $valid_keys, true)) {
            $correctKey = $this->getClosestMatch($key, $valid_keys);
            if ($correctKey !== null) {
                  $segments[0] = $correctKey;
                  $correct_url = '/' . implode('/', $segments);
                  header("Location: $correct_url", true, 301);
                  exit();
            }
            return $result;
         }

         // Если value — число => id
         if (ctype_digit($value)) {
            $result[1] = (int)$value;
            return $result;
         }

         // Если value — валидный slug
         if (in_array($value, $valid_values, true)) {
            $result[0] = $value;
            return $result;
         }
   
         // Попытка исправить value
         $closestVal = $this->getClosestMatch($value, $valid_values);
         if ($closestVal !== null) {
            $segments[1] = $closestVal;
            $correct_url = '/' . implode('/', $segments);
            header("Location: $correct_url", true, 301);
            exit();
         }
      }

      return $result;
   }
   

   function getClosestMatch(string $input, array $dictionary, int $threshold = 3): ?string {
      if (trim($input) === '') return null;
      $inputArray = preg_split('//u', mb_strtolower($input), -1, PREG_SPLIT_NO_EMPTY);
      $bestMatch = null;
      $minDistance = $threshold + 1;
      foreach ($dictionary as $word) {
          $wordArray = preg_split('//u', mb_strtolower($word), -1, PREG_SPLIT_NO_EMPTY);
          if (abs(count($inputArray) - count($wordArray)) >= $minDistance) continue;
          $dist = $this->calculateDamerauLevenshtein($inputArray, $wordArray);
          if ($dist === 0) return $word;
          if ($dist < $minDistance) {
              $minDistance = $dist;
              $bestMatch = $word;
          }
      }
      return ($minDistance <= $threshold) ? $bestMatch : null;
  }
  
  function calculateDamerauLevenshtein(array $a, array $b): int {
      $len1 = count($a);
      $len2 = count($b);
      if ($len1 < $len2) return $this->calculateDamerauLevenshtein($b, $a);
      $prev = range(0, $len2);
      $prevPrev = [];
      for ($i = 1; $i <= $len1; $i++) {
          $curr = [$i];
          for ($j = 1; $j <= $len2; $j++) {
              $cost = ($a[$i - 1] === $b[$j - 1]) ? 0 : 1;
              $curr[$j] = min($curr[$j - 1] + 1, $prev[$j] + 1, $prev[$j - 1] + $cost);
              if ($i > 1 && $j > 1 && $a[$i - 1] === $b[$j - 2] && $a[$i - 2] === $b[$j - 1]) {
                  $curr[$j] = min($curr[$j], ($prevPrev[$j - 2] ?? PHP_INT_MAX) + $cost);
              }
          }
          $prevPrev = $prev;
          $prev = $curr;
      }
      return $prev[$len2];
  }
  


   /**
    * Ищет наиболее похожее слово в массиве, используя алгоритм Дамерау-Левенштейна.
    * 
    * @param string $input Поисковое слово
    * @param array $dictionary Массив слов для сравнения
    * @param int $threshold Максимально допустимое количество правок (по умолчанию 3)
    * @return string|null Исправленное слово или null, если ничего не найдено
    *//*
   function getClosestMatch(string $input, array $dictionary, int $threshold = 3): ?string 
   {
       if (empty($input)) return null;
   
       // Подготовка входного слова (разбиваем на символы один раз)
       $inputArray = mb_str_split($input);
       $inputLen = count($inputArray);
       
       $bestMatch = null;
       $minDistance = $threshold + 1;
   
       foreach ($dictionary as $word) {
           $wordArray = mb_str_split($word);
           $wordLen = count($wordArray);
   
           // Оптимизация 1: Быстрая проверка разницы длин
           if (abs($inputLen - $wordLen) >= $minDistance) continue;
   
           // Оптимизация 2: Вычисление расстояния с малым потреблением памяти
           $dist = $this->calculateDamerauLevenshtein($inputArray, $wordArray);
   
           // Если нашли точное совпадение — сразу отдаем
           if ($dist === 0) return $word;
   
           // Если это слово ближе, чем предыдущие найденные
           if ($dist < $minDistance) {
               $minDistance = $dist;
               $bestMatch = $word;
           }
       }
   
       return $bestMatch;
   }
   
   /**
    * функция расчета (Дамерау-Левенштейн)
    *//*
   function calculateDamerauLevenshtein(array $a, array $b): int 
   {
       $len1 = count($a);
       $len2 = count($b);
       
       // Экономим итерации: первая строка всегда длиннее
       if ($len1 < $len2) return calculateDamerauLevenshtein($b, $a);
   
       $prev = range(0, $len2);
       $prevPrev = [];
   
       for ($i = 1; $i <= $len1; $i++) {
           $curr = [$i];
           for ($j = 1; $j <= $len2; $j++) {
               $cost = ($a[$i - 1] === $b[$j - 1]) ? 0 : 1;
               $curr[$j] = min(
                   $curr[$j - 1] + 1,        // Вставка
                   $prev[$j] + 1,            // Удаление
                   $prev[$j - 1] + $cost     // Замена
               );
   
               // Проверка на перестановку (транспозицию) соседних знаков
               if ($i > 1 && $j > 1 && $a[$i - 1] === $b[$j - 2] && $a[$i - 2] === $b[$j - 1]) {
                   $curr[$j] = min($curr[$j], ($prevPrev[$j - 2] ?? 0) + $cost);
               }
           }
           $prevPrev = $prev;
           $prev = $curr;
       }
       return $prev[$len2];
   }
*/   
   /* -------------------------------- */

   public function getPath(): string
   {  
      if (str_contains($this->formatted_uri, '?')) {
         $path = explode('?', $this->formatted_uri);
         return $path[0];

      }
      return $this->formatted_uri;

   }

/*   
   public function getRequestParams()
   {
      if (!str_contains($this->formatted_uri, "&")) {
         return $this->getPath();

      } else if (str_contains($this->formatted_uri, "&")) {
         $arr_params = explode("&", $this->formatted_uri);
         foreach ($arr_params as $param) {
            if (preg_match('~^[a-z0-9-]+$~', $param, $matches)) {
               $page = implode($matches);
               $key_page = array_search($page, $arr_params);
               $new_params = array_slice($arr_params, 0, $key_page, true);
               $path = implode("?", $new_params);
               return $path;
            }
         }
      }
      return $this->formatted_uri;

   }

   public function get_id()
   {
      if (str_contains($this->formatted_uri, '/')) {
         $arr_params = explode('/', $this->formatted_uri);
         $id = array_pop($arr_params);
         $id = preg_replace('~[^0-9]~i', '', $id); // ^ допуская игнорирует буквы
         return (int)$id;
      }

   }  

   public function get_slug()   
   {
      $item = explode('/', trim($this->formatted_uri, '/')); // trim убирает крайние слеши

      return $item[1];

   }
 
*/

   public function getData(): array
   {
      $data = [];
      $request_data = $this->isPost() ? $_POST : $_GET;

      foreach ($request_data as $k => $v) {
         if (is_string($v)) {
            $v = strip_tags(trim($v));
         }
         $data[$k] = $v;
      
      }
      return $data;
   }

}
