<?php
namespace Master;

class Cache
{
   public function set($key, $data, $seconds = 3600): void
   {
      if (! file_exists(CACHE)) {
         mkdir(CACHE);
      }
      $content[$key] = $data;
      $content['time'] = time() + $seconds;
      $cache_file = CACHE . '/' . md5($key) . '.txt';
      
      file_put_contents($cache_file, serialize($content));
   
   }

   public function get($key, $default = null)
   {
      $cache_file = CACHE . '/' . md5($key) . '.txt';
      
      if (file_exists($cache_file)) {
         $content = unserialize(file_get_contents($cache_file));
         
         if (isset($content['time']) && time() <= $content['time']) {
            return $content[$key];
         
         }
         unlink($cache_file);
         //file_put_contents($cache_file, "");
      
      }
      return $default;
   
   }

   public function removeCache($key): void
   {
      $cache_file = CACHE . '/' . md5($key) . '.txt';
      
      if (file_exists($cache_file)) {
         unlink($cache_file);
         //file_put_contents($cache_file, "");
      
      }
   }


}
