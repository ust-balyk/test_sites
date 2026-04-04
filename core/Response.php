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
         $redirect = filter_var($url, FILTER_SANITIZE_URL);
         //$redirect = $url;

      } else {
         $redirect = base_url('/'); 

      }
      header("Location: $redirect");
      exit;

   }

}
