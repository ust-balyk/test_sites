<?php
namespace Master;

class Request
{
   public string $formatted_uri;  // отформатированая строка запроса

   public function __construct ($uri)
   {
      // $uri = trim(urldecode($uri));
      //
      $uri = (string) $uri;
      // на path и query
      $parts = parse_url($uri);
      // оставляем только разрешённые символы и убираем лишние слэши
      $path = $parts['path'] ?? '/';
      $path = str_replace(['../','..\\'], '', $path);
      $path = preg_replace('~[^a-zA-Z0-9\-/]~', '', $path);
      $path = '/' . trim($path, '/'); // гарантируем один ведущий слэш
      // оставляем только ключ=значение пар и кодируем значения
      $query = '';
      if (!empty($parts['query'])) {
         parse_str($parts['query'], $qs);
         $params = [];
         foreach ($qs as $k => $v) {
            // разрешаем только буквенно-цифровые ключи и дефис; фильтруем значения
            if (preg_match('~^[a-zA-Z0-9-]+$~', $k)) {
               $allowed[$k] = rawurlencode((string) $v);
               //$allowed[$k] = (string)$v; // НЕ rawurlencode
            }
         }
         if ($params) {
            $query = http_build_query($params, '', '&', PHP_QUERY_RFC1738);
            //$query = http_build_query($allowed, '', '&', PHP_QUERY_RFC3986);
         }
      }
      // нормализованная строка без лишних символов
      $this->formatted_uri = strtolower(ltrim($path, '/')) . ($query ? '?' . $query : '');
      
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


   public function getSLUG_or_ID()
   {
      $uri = $this->formatted_uri;
      $uri = strstr($uri, '?', true) ?: $uri;
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
  
   public function getPath(): string
   { 
      $uri = $this->formatted_uri ?? '';
      $pos = strpos($uri, '?');
      $path = $pos === false ? $uri : substr($uri, 0, $pos);
      return ltrim($path, '/');
      
   }

   public function getRequestParams()
   {
      $parts = parse_url($this->formatted_uri);

      $uri = $parts['path'];

      if (!empty($parts['query']) && strpos($parts['query'], '=') !== false) {
         parse_str($parts['query'], $params); // &$params
         if (isset($params['page'])) {
            unset($params['page']);
         }
         if (!empty($params)) {
            $uri .= '?'. http_build_query($params, '', '&', PHP_QUERY_RFC1738);
         }
      }
      return $uri;
   }

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
