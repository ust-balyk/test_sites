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
         //$redirect = $_SERVER['HTTP_REFERER'] ?? base_url('/');
         $redirect = base_url('/'); 

      }
      
      header("Location: $redirect");
      die;

   }

}
