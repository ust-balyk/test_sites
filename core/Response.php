<?php
namespace Master;

class Response
{   
   public function set_response_code($code)
   {
      return http_response_code($code);

   }


   public function redirect($url='')
   {
      if ($url) {
         $redirect = $url;

      } else {
         $redirect = base_url('/'); 

      }
      header("Location: $redirect");
      exit;

   }


   public function safeRedirect($url) 
   {
      // URL пустой или ведет на другой домен — только на главную
      if (empty($url)) { return '/'; }

      // Парсим URL, чтобы проверить хост
      $parts = parse_url($url);
      $currentHost = $_SERVER['HTTP_HOST'];

      // Если в ссылке указан чужой хост — это попытка подставы
      if (isset($parts['host']) && $parts['host'] !== $currentHost) {
         return '/';
      
      }

      // Защита от редиректа на саму регистрацию (чтобы не зациклить)
      if (isset($parts['path']) && $parts['path'] === '/register') {
         return '/';
      
      }

      return $url;
   
   }


}
