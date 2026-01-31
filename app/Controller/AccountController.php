<?php
namespace App\Controller;

class AccountController
{
    static function index()
    {
        if ($email = session()->get('email')) {
            
            // findOne($tbl, $key = 'id', $value = null)
            // в указанной таблице по ключу найти все данные значения из сессии
            $user = db()->findOne('users', 'email', $email);
            return app()->view->account (    
                
                'index', // подключить файл account/index.php
                
                [        // создать массив данных
                    'user' => $user,
                ],
            
            );
        }
        return app()->response->redirect('/login');
    }
    
}
