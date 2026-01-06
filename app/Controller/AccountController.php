<?php
namespace App\Controller;

class AccountController
{
    static function index()
    {
        if ($email = session()->get('email')) {
            
            $user = db()->findOne('users', $email, 'email');
            return app()->view->account (    
                
                'index',
                
                [
                    'user' => $user,
                ],
            
            );
        }
        return app()->response->redirect('/login');
    }
    
}
