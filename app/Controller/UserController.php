<?php
namespace App\Controller;
use App\Login\User;

class UserController
{
    public function register()
    {
        return p_view('/logins/register', []);
        //return f_view('', '../logins/register', []);
 
    }
    
    public function login()
    {
        return p_view('/logins/login', []);

    }

    public function logout()
    {
        include CONSTRUCT .'/logins/logout.php';

    }
    
    private function filterName($Name): bool|string
    {
        // удалить из строки все скобки () вместе с их содержимым
        $test = preg_replace('#\(((?>[^()]+)|(?R))*\)#', '', $Name);

        $name_taken = ['vadim islamov','islamov vadim','исламов вадим','вадим исламов'];
        $test = str_replace($name_taken, '', $test);
        
        $dross = [ "‘", "`", "'", '"', '!', '@', '#', '$', '%', '\\','^', '&',
                   "’", '“', '*', '(', ')', '_', '+', '{', '}', '|', ':', '.', "´",
                   '<', '>', '?', '[', ']', ';', '=', '--', '~', '/', '#', ',' ];
        $test = str_replace($dross, '', $test);
        
        $test = preg_replace('#<.*>#', '', $test); // удалить все HTML-теги из строки $test 
        $test = preg_replace('#\s\s+#',' ',$test); // убрать двойные пробелы
        
        $Name = strtolower($test);
        if ($Name > 0) {
            return mb_convert_case($Name, MB_CASE_TITLE, "UTF-8");
        } else {
            return false;
        }
    }

    private function filterEmail($email): bool
    {
        $pattern = '#^[a-zA-Z0-9._+-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,}$#i';
        if (preg_match($pattern, $email)) {
            return true;
        
        }
        return false;

    }
    
    public function record()
    {
        if (! app()->router->validateCsrfToken()) {
            session()->setFlash('error', 'Нарушение норм безопасности');
            app()->response->redirect('/register');

        } else {
            $user = new User();
            $user->loadData();
        
            if (! $user->validate()) {
                session()->setFlash('error', 'Пожалуйста, проверьте введённые данные');
                session()->set('form_data', $user->attributes);
                session()->set('form_errors', $user->getErrors());
                app()->response->redirect('/register');
            
            } else {
                
                if ($user->attributes['name'] = $this->filterName($user->attributes['name'])) {
                    
                    if ($this->filterEmail($user->attributes['email'])) {
                        
                        if (! db()->emailExists($user->attributes['email'])) {

                            $password = $user->attributes['password'];

                            if (! defined('HASH_COST'))  {
                                $time_cost = TIME_COST;     // Целевое время
                                $cost = 10;                 // Минимальный порог
                            
                                do {
                                    $cost++;
                                    $start = microtime(true);
                                    password_hash($password, PASSWORD_BCRYPT, ["cost" => $cost]);
                                    $end = microtime(true);
                                
                                } while (($end - $start) < $time_cost);
                                    $cost = ($cost - 1);
                                    $options = ['cost' => $cost]; // выпадает 10 ~ 15*/
                                    $user->attributes['password'] = password_hash($password, 
                                        PASSWORD_DEFAULT, $options);
                                    
                                    define('HASH_COST', $cost);

                            } else {
                                $user->attributes['password'] = password_hash($password,
                                    PASSWORD_DEFAULT, HASH_COST);

                            }


                            if ($user->save()) {

                                session()->set('csrf_token', (string)bin2hex(random_bytes(32)));
                                session()->set('user.email', $user->attributes['email']);
                                session()->set('user.name', $user->attributes['name']);
                                $user_23 = db()->getLastId();
                                db()->set_token_23($user_23);
                                //$redirect_back = session()->get('return_to') ?? '/';
                                //session()->remove('return_to'); // Очищаем после использования
                                //app()->response->redirect($redirect_back);
                                //app()->response->redirect('/');
                                //echo "<script>window.history.go(-3);window.location.reload();</script>";

                            }

                        } else {
                            session()->setFlash('error', 'Укажите другой адрес или аутентифицируйтесь');
                            app()->response->redirect('/register');
                        }

                    } else {
                        session()->setFlash('error', 'Пожалуйста, введите корректный адрес электронной почты');
                        app()->response->redirect('/register');
                    }

                } else {
                    session()->setFlash('error', 'Данное имя занято, пожалуйста, выберите любое другое');
                    app()->response->redirect('/register');
                }
            }
        }
    }

    public function enter()
    {
        if (! app()->router->validateCsrfToken() ) {
            session()->setFlash('error', 'Нарушение норм безопасности');
            app()->response->redirect('/login');
        
        } else {

            if ($email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) && 
                $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {

                if ($user_id = db()->realUser($email, $password)) { 
                    db()->set_token_23($user_id);
                    //$redirect_back = session()->get('return_to') ?? '/';
                    //session()->remove('return_to'); // Очищаем после использования
                    //app()->response->redirect($redirect_back);

                } else {
                    session()->setFlash('error', 'Пожалуйста, проверьте введённые данные');
                    app()->response->redirect('/login');
                
                }
            } else {
                session()->setFlash('error', 'Пожалуйста, заполните все поля');
                app()->response->redirect('/login');
            
            }
        }  
    }


}
