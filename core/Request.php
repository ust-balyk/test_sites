<?php
namespace Master;

class Request
{
   public string $formatted_uri; # отформатированая строка запроса

   public function __construct($uri)
   {
      $sanitized_uri = filter_var($uri, FILTER_SANITIZE_URL);
      //$sanitized_uri = preg_replace('~[^a-zA-Z0-9_/]~', '', $uri);
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

   public function get($key, $default_value = null): ?string
   {  
      return $_GET[$key] ?? $default_value;

   }

   public function post($key, $default_value = null): ?string
   {  
      return $_POST[$key] ?? $default_value;

   }

   public function get_ID_or_SLUG()
   {
      $uri = $this->formatted_uri;

      define('valid_keys', ['cosmetics', 'product']);
      
      $segments = explode('/', trim($uri, '/'));
      $count = count($segments);

      // Валидация структуры: только 1 или 2 полные пары
      // типа /cosmetics/for-body или /cosmetict/for-body/product/11140
      if ($count !== 2 && $count !== 4) {
         return response()->redirect('/');
      
      }
      $result = null;
      // Проходим по парам
      for ($i = 0; $i < $count; $i += 2) {
         $key = $segments[$i];
         $value = $segments[$i + 1];
         // Проверка ключа
         if (!in_array($key, valid_keys)) {
            return response()->redirect('/');
         
         }
         // Проверка и типизация значения
         if (preg_match('~^\d+$~', $value)) {
            $result = (int)$value;

         } elseif (preg_match('~^[a-z_]+$~i', $value)) {
            $result = (string)$value;

         } else {
            return response()->redirect('/');

         }
         // Если это была единственная пара, цикл завершится сам.
         // Если пар две, цикл перезапишет $result значением из второй пары.
      }
      return $result;
   
   }

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
