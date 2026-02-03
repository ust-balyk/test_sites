<?php
namespace App\Login;
use Master\Logon;

class User extends Logon
{
    protected string $table = 'users';

    //public bool $timestamps = false;
    
    # поля для валидации
    protected array $loaded = ['name', 'email', 'password', 'confirm_password', 'auth_token'];
    
    # поля для базы данных
    protected array $fillable = ['name', 'email', 'password', 'auth_token'];

    # правила проверки
    protected array $rules = [
        'required'         => ['name', 'email', 'password', 'auth_token'],
        'lengthMax'        => [['name', 50], ['email', 100]],
        'email'            => [['email']],
        //'lengthMin'    => [['password', 6]],
        'equals'           => [['password', 'confirm_password']],
    ];

    # свои метки
    protected array $labels = [
        'name'              => "поле 'Имя'",
        'email'             => "поле 'Электронная почта'",
        'password'          => "поле 'Пароль'",
        'confirm_password'  => 'Подтвердить пароль',
    ];

}
