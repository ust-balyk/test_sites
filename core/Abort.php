<?php
namespace Master;

class Abort
{
   function error($code=404)
   {
      Application::$app->response->set_response_code($code);

      Application::$app->view->resource_files("/errors/{$code}");

      exit();
   
   }


}
