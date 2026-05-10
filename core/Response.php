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

   
   public function json($data, $code = 200)
   {
      http_response_code($code);
      header("Content-type: application/json; charset=UTF-8");
      exit(json_encode($data));
   
   }


   public function text($data, $code=200)
   {
      http_response_code($code);
      header("Content-type: text/html; charset=UTF-8");
      exit($data);

   }


}
