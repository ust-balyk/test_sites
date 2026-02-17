<?php
namespace Master;
use PDO;

class Database
{

    protected \PDO $connection;
    protected \PDOStatement $stmt;

    public function __construct()
    {
        $dsn = "mysql:host=". DB_SETTINGS['host'] .
                   ";dbname=".DB_SETTINGS['database'] .
                 ";charset=". DB_SETTINGS['charset'];

        try
        { 
            $this->connection = new \PDO( $dsn,
                DB_SETTINGS['username'],
                DB_SETTINGS['password'],
                DB_SETTINGS['options' ],
            );

            $tbl_users = "CREATE TABLE IF NOT EXISTS `users`
                (
                    `id` int(11) AUTO_INCREMENT PRIMARY KEY,
                    `name` varchar(55) NOT NULL,
                    `email` varchar(155) UNIQUE NOT NULL,
                    `password` varchar(255) NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    `auth_token` varchar(255) DEFAULT NULL
                )
                ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $this->connection->exec($tbl_users);
            
            $tbl_categories = "CREATE TABLE IF NOT EXISTS `categories`
                (
                    `id` int(10) UNSIGNED NOT NULL PRIMARY KEY,
                    `title` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `slug` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE KEY,
                    `parent_id` int(10) UNSIGNED NOT NULL DEFAULT '0'
                ) 
                ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $this->connection->exec($tbl_categories);

        }
        catch (\PDOException $e)
        {
            date_default_timezone_set('Asia/Yekaterinburg');
            error_log("[" . date('Y-m-d H:i:s') . "] DB Error: {$e->getMessage()}" .
                PHP_EOL, 3, ERROR_LOGS);
            echo "<p style='color:white'>b</p>";
            Application::$app->abort->error(500);
        }
        return $this;
    
    }
    
    public function query(string $query, array $params = []): static
    {
        try
        {
            $this->stmt = $this->connection->prepare($query);
            $this->stmt->execute($params);
        }
        catch (\PDOException $e)
        {
            date_default_timezone_set('Asia/Yekaterinburg');
            error_log("[" .date('Y-m-d H:i:s'). "] {$e->getMessage()}" .
                PHP_EOL, 3, ERROR_LOGS);
        }
        return $this;
    
    }

    public function get(): array|false
    {
        return $this->stmt->fetchAll();
    
    }

    public function getAssoc($key='id'): array
    {
        $data = [];
        while ($row = $this->stmt->fetch()) {
            $data[$row[$key]] = $row;
        
        }
        return $data;
    
    }

    public function getOne()
    {
        return $this->stmt->fetch();
    
    }

    public function getColumn($tbl) // получить количество записей
    {
        $this->query("SELECT COUNT(*) FROM {$tbl}");
        return $this->stmt->fetchColumn();
    
    }

    public function findAll($tbl): array|false
    {
        $this->query("select * from {$tbl}");
        return $this->stmt->fetchAll();
    
    }

    public function findOne($tbl, $key= 'id', $value= null)
    {
        $this->query("select * from {$tbl} where $key=? LIMIT 1", [$value]);
        return $this->stmt->fetch();
    
    }

    public function emailExists($email): bool
    {
        if ($email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
            $this->query("select * from users where email=? LIMIT 1", [$email]);
            
            if ($this->stmt->fetch()) {
                return true;
            }
            return false;
        }
        return false;
    
    }
    
    public function realUser($email, $password)
    {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $this->query("select * from users where email=? LIMIT 1", [$email]);
        
        if ($user = $this->stmt->fetch()) {
            
            if (password_verify($password, $user['password'])) {

                Application::$app->session->remove($_SESSION['csrf_token']);
                $_SESSION['csrf_token'] = bin2hex(random_bytes(64));
                Application::$app->session->set('email', $user['email']);
                Application::$app->session->set('name', $user['name']);

                return true;

            }
            return false;
        }
        return false;
    
    }
    
    /* авторизация cookie */
    
    public function generate_token()
    {
        $auth_token = str_shuffle('+_@=&:<`/-|~.>;\"$;#'. bin2hex(random_bytes(16)));
        return $auth_token;

    }
     
    public function set_auth_token()
    {
        $auth_token = $this->generate_token();
        $hash_token = password_hash($auth_token, PASSWORD_DEFAULT);

        $options = array (
            'expires'  => time() + 31536000,
            'path'     => '/',
            'secure'   => true,      // or false
            'httponly' => true,     // or false
            'samesite' => 'Strict' // None || Lax || Strict
        );

        setcookie('~23~', $hash_token, $options);
        return $auth_token;

    }
    
    public function user_back()
    {
        if (! isset($_SESSION['name']) && isset($_COOKIE['~23~'])) {

            $hash_token = $_COOKIE['~23~'];

            $this->query('SELECT * FROM `users`');

            while($user = $this->stmt->fetch()){
                
                if (password_verify($user['auth_token'], $hash_token)) {
                
                    Application::$app->session->remove($_SESSION['csrf_token']);
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(64));
                    Application::$app->session->set('email', $user['email']);
                    Application::$app->session->set('name', $user['name']);

                }
                return false;
            }
        }
        return false;

    }

    /* авторизация cookie END */


    public function findOrFail($tbl, $value, $key = 'id')
    {
        $res = $this->findOne($tbl, $value, $key);
        
        if (!$res) {
            
            Application::$app->abort->error();
        
        }
        return $res;
    
    }

    public function getInsertId(): false|string
    {
        return $this->connection->lastInsertId();
    
    }

    public function rowCount(): int
    {
        return $this->stmt->rowCount();
    
    }

    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    
    }

    public function commit(): bool
    {
        return $this->connection->commit();
    
    }

    public function rollBack(): bool
    {
        return $this->connection->rollBack();
    
    }

}
