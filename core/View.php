<?php
namespace Master;

class View
{

   public function full_view($layout, $view, $data=[])
   {  
      extract($data);
        
      $view_file = VIEWS . "/{$view}.php";
      if (!is_file($view_file)) $view_file = DEF_VIEW;
      
      if (is_file($view_file)) {
         ob_start();
         include $view_file;
         $view_file = ob_get_clean();
      
      } else {
         echo "не найден файл --> " . basename($view_file);
         Application::$app->abort->error(500);
      
      }
      
      ob_start();
      
      $layout_file = LAYOUTS . "/{$layout}.php";
      if (!is_file($layout_file)) $layout_file = DEF_LAYOUT;
      
      if (is_file($layout_file)) {
         ob_start();
         include $layout_file;
         return ob_get_clean();

      } else {
         echo "не найден файл --> " . basename($layout_file);
         Application::$app->abort->error(500);

      }
   
   }
   
   public function partial_view($view, $data=[])
   {
      extract($data);
      # view файл со своим подключением к стилям
      if (is_file($view_file = CONSTRUCT . "/{$view}.php")) {
         ob_start();
         include $view_file;
         return ob_get_clean();
      
      } else {
         echo "<pre><br>  не найден файл -> " .
            basename($view_file) . "</pre>";
         Application::$app->abort->error(500);
      
      }
   }

   public function resource_files($resource_file)
   {
      $file = CONSTRUCT . $resource_file . ".php";
      if (is_file($file)) {
         include $file;

      } else {
         echo "<pre><br>  не найден файл -> " .
            basename(CONSTRUCT . $resource_file . ".php") . "</pre";
       
      }
   }

   public function account($view, $data=[])
   {
      extract($data);
      
      if (is_file($view_file = ACCOUNT . "/{$view}.php")) {
         ob_start();
         include $view_file;
         return ob_get_clean();
      
      } else {
         echo "<pre><br>  не найден файл -> " .
            basename($view_file) . "</pre>";
         Application::$app->abort->error(500);
      
      }
   }

   public function admin($view, $data=[])
   {
      extract($data);
      
      if (is_file($view_file = ADMIN . "/{$view}.php")) {
         ob_start();
         include $view_file;
         return ob_get_clean();
      
      } else {
         echo "<pre><br>  не найден файл -> " .
            basename($view_file) . "</pre>";
         Application::$app->abort->error(500);
      
      }
   }

}
