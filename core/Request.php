<?php
namespace Master;

class Request
{
   public string $formatted_uri; # отформатированая строка запроса

   public function __construct($uri)
   {
      $this->formatted_uri = strtolower(trim($uri, '/'));
      
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
      
   public function getPath(): string
   {  
      if (str_contains($this->formatted_uri, "?")) {
         $path = explode("?", $this->formatted_uri);
         return $path[0];

      }
      return $this->formatted_uri;

   }
   
   public function getRequestParams()
   {
      if (!str_contains($this->formatted_uri, "&")) {
         return $this->getPath();

      } else if (str_contains($this->formatted_uri, "&")) {
         $arr_params = explode("&", $this->formatted_uri);
         foreach ($arr_params as $param) {
            if (preg_match('|^page=[0-9]+$|', $param, $matches)) {
               $page = implode($matches);
               $key_page = array_search($page, $arr_params);
               $new_params = array_slice($arr_params, 0, $key_page, true);
               $path = implode("&", $new_params);
               return $path;
            }
         }
      }
      return $this->formatted_uri;

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
