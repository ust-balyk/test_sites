<?php
namespace Master;

class Request
{
   public string $formatted_uri;  // отформатированая строка запроса

   public function __construct($uri)
   {
      //error_log("URI_BEFORE: '".$_SERVER['REQUEST_URI']."'");
      
      $sanitized_uri = preg_replace('~[^a-zA-Z0-9-/]+~i', '', $uri);
      $this->formatted_uri = strtolower(trim($sanitized_uri, '/'));
      
      //error_log("URI_AFTER_NORMALIZE: '".trim($_SERVER['REQUEST_URI'])."'");
      
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

   
   public function get_SLUG_or_ID()
   {
      $uri = $this->formatted_uri;
      // Инициализируем массив [slug, id] пустыми значениями
      $result = [null, null]; 

      $valid_keys = [
         'cosmetics', 
         'product'
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

      if ($count < 2) {
         header("Location: /cosmetics", true, 302);
         exit();
      
      }

      if ($count === 2 || $count === 4) {
         for ($i = 0; $i < $count; $i += 2) {
            $key = $segments[$i];
            $value = $segments[$i + 1] ?? null;

            if (in_array($key, $valid_keys)) {
               
               if (ctype_digit($value)) {
                  // Записываем ID во второй индекс (1)
                  $result[1] = (int)$value;
               
               } elseif (in_array($value, $valid_values)) {
                  // Записываем SLUG в первый индекс (0)
                  $result[0] = (string)$value;
               
               } else {
                  // Проверка опечаток
                  foreach ($valid_values as $valid) {
                  
                     if (levenshtein((string)$value, $valid) <= 2) {
                           $segments[$i + 1] = $valid;
                           $correct_url = '/' . implode('/', $segments);
                           header("Location: $correct_url", true, 301);
                           exit();
                     }
                  }
               }
            }
         }
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
