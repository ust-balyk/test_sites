<?php
namespace App\Lock;
use Master\Application;

class Friend
{
   public function lock()
   {
      if (session()->get('user.name')) {
  
         response()->redirect('/');

      }

   }


}
