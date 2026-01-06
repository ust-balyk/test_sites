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
        
        $dross = [ "‘", "`", "'", '"', '!', '@', '#', '$', '%', '\\','^', '&', '-',
                   "’", '“', '*', '(', ')', '_', '+', '{', '}', '|', ':', '.', "´",
                   '<', '>', '?', '[', ']', ';', '=', '--', '~', '/', '#', ',' ];
        $test = str_replace($dross, '', $test);
        
        $test = preg_replace('#[0-1]#','', $test); // удалить любой один символ
        $test = preg_replace('#<.*>#', '', $test); // удалить все HTML-теги из строки $test 
        $test = preg_replace('#\s\s+#',' ',$test); // убрать двойные пробелы
        
        $Name = strtolower($test);
        if ($Name > 0) {
            return mb_convert_case($Name, MB_CASE_TITLE, "UTF-8");
        } else {
            return false; //return "Richard Roe";
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
                        
                        if ( ! db()->emailExists($user->attributes['email']) ) {
                            
                            $timeTarget = 0.500;     // Целевое время
                            $cost = 10;          // Минимальный порог
                            $password = $user->attributes['password'];

                            do {
                                $cost++;
                                $start = microtime(true);
                                password_hash($password, PASSWORD_BCRYPT, ["cost" => $cost]);
                                $end = microtime(true);
                            } while (($end - $start) < $timeTarget);
                            $cost = ($cost - 1);
                            $options = ['cost' => $cost]; // устанавливаем сложность 10-15
                            $user->attributes['password'] = password_hash($password, PASSWORD_DEFAULT, $options);

                            if ($user->save()) {
                                session()->set('email', $user->attributes['email']);
                                session()->set('name', $user->attributes['name']);
                                //app()->response->redirect('/');
                                echo "<script>window.history.go(-3)</script>";
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

            if ($email = request()->post('email') && $password = request()->post('password')) {
              
                $email = filter_var($email, FILTER_SANITIZE_EMAIL); 
                $password =  htmlentities($password);
                
                if (db()->realUser($email, $password)) {
                    echo "<script>window.history.go(-2)</script>";

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
