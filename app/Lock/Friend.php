<?php
namespace App\Lock;
use Master\Application;

class Friend
{
   function lock()
   {
      if (session()->get('name')) {
  
         response()->redirect('/');
  
      }

   }


}
