<?php
namespace App\Lock;
use Master\Application;

class Guest
{
   public function lock()
   {
      if (! session()->get('user.name')) {

         response()->redirect('/login');

      }

   }   

}
