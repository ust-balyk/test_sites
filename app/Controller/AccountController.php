<?php
namespace App\Controller;

class AccountController
{
    static function index()
    {
        if ($email = session()->get('user.email')) {
            
            // findOne($tbl, $key = 'id', $value = null)
            // в указанной таблице по ключу найти все данные значения из сессии
            $user = db()->findOne('users', 'email', $email);
            // например, соединение с БД упало
            if ($user) {
                return app()->view->account (    
                
                    'index', // подключить файл account/index.php              
                    [        // создать массив данных
                        'user' => $user,
                    ],
                );
            }
            return app()->response->redirect('/login');
        }
        return app()->response->redirect('/login');
    }
    
}

/*
class AccountController
{
    static function index()
    {
        // получить весь массив user
        //
        //$userSession = session()->get('user');
        //$email = $userSession['email'] ?? null;
        //

        $email = session()->get('user.email');

        if (!$email) {
            return app()->response->redirect('/login');
        }

        // искать по колонке email
        $user = db()->findOne('users', 'email', $email);
        if (!$user) {
            return app()->response->redirect('/login');
        }

        return app()->view->account('index', ['user' => $user]);
    }

}
 */
