<?php
namespace App\Lock;
use Master\Application;

class Guest
{
   function lock()
   {
      if (! session()->get('name')) {

         response()->redirect('/login');

      }

   }   

}
