<?php
namespace Master;
use PDO;

class Database
{

    protected \PDO $connection;
    protected \PDOStatement $stmt;

    public function __construct()
    {
        $dsn = "mysql:host=". DB_SETTINGS['host'] .";charset=". DB_SETTINGS['charset'];

        try
        { 
            $this->connection = new \PDO( $dsn,
                DB_SETTINGS['username'],
                DB_SETTINGS['password'],
                DB_SETTINGS['options' ],
            );
            
            if ($japan_in_ru = "CREATE DATABASE IF NOT EXISTS `japan_in_ru`
                                CHARACTER SET utf8mb4
                                COLLATE utf8mb4_unicode_ci;")
            {
                $this->connection->exec($japan_in_ru);
                $this->connection->exec("USE japan_in_ru");

                //1 — пользователь, 2 — модератор, 3 — администратор 
                $tbl_users = "CREATE TABLE IF NOT EXISTS `users` (
                        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        `name` VARCHAR(55) NULL DEFAULT NULL,
                        `email` VARCHAR(105) UNIQUE NOT NULL,
                        `password` VARCHAR(255) NOT NULL,
                        `role` INT DEFAULT 1,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        `auth_token` VARCHAR(255) DEFAULT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
                $this->connection->exec($tbl_users);
            
                $tbl_categories = "CREATE TABLE IF NOT EXISTS `categories` (
                    `id` INT PRIMARY KEY,
                    `name` VARCHAR(55) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `slug` VARCHAR(55) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE KEY,
                    `parent_id` INT NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                    INSERT IGNORE INTO categories (`id`, `name`, `slug`, `parent_id`) VALUES
                    (1, 'В наличии', 'in-stock', 0),
                    (2, 'Новинки', 'new', 0),
                    (3, 'Популярные товары', 'hit-sales', 0),
                    (4, 'Товары по акции', 'promo', 0),
                    (5, 'Твоё здоровье', 'bless-you', 0),
                    (6, 'Японская косметика', 'cosmetics', 0),
                    (7, 'Для мужчин', 'for-men', 0),
                    (8, 'Для детей', 'for-children', 0),
                    (9, 'Продукты питания', 'foodstuffs', 0),
                    (10, 'Товары для дома', 'household-goods', 0),
                    (11, 'Приборы и массажеры', 'devices', 0),
                    (12, 'Зоотовары', 'pet-supplies', 0);";
                $this->connection->exec($tbl_categories);

                $tbl_start = "CREATE TABLE IF NOT EXISTS `start` (
                    `id` INT PRIMARY KEY,
                    `name` VARCHAR(55) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `slug` VARCHAR(55) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE KEY,
                    `parent_id` INT NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                    INSERT IGNORE INTO start (`id`, `name`, `slug`, `parent_id`) VALUES
                    (1, 'декоративная косметика', 'makeup', 0),
                    (2, 'для лица', 'for-face', 0),
                    (3, 'для полости рта', 'for-oral-cavity', 0),
                    (4, 'для волос', 'for-hair', 0),
                    (5, 'для тела', 'for-body', 0),
                    (6, 'для рук', 'for-hands', 0),
                    (7, 'для ног', 'for-feet', 0),
                    (8, 'ароматерапия', 'aromatherapy', 0),
                    (9, 'подарочные наборы', 'sets-gift', 0),
                    (10, 'аксессуары', 'accessories', 0);";
                $this->connection->exec($tbl_start);

                $tbl_products = "CREATE TABLE IF NOT EXISTS `products` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `category` TEXT NOT NULL,
                    `slug` VARCHAR(50) NOT NULL,
                    `outer_id` INT NOT NULL UNIQUE KEY,
                    `name` TEXT NOT NULL,
                    `description` TEXT DEFAULT NULL,
                    `image` VARCHAR(50) DEFAULT NULL,
                    `price` TEXT DEFAULT NULL,
                    `old_price` TEXT DEFAULT NULL,
                    `new_price` TEXT DEFAULT NULL,
                    `category_id` INT
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
                $this->connection->exec($tbl_products);

                $tbl_cosmetics = "CREATE TABLE IF NOT EXISTS `cosmetics` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `category` TEXT NOT NULL,
                    `slug` VARCHAR(50) NOT NULL,
                    `outer_id` INT NOT NULL UNIQUE KEY,
                    `name` TEXT NOT NULL,
                    `description` TEXT DEFAULT NULL,
                    `image` VARCHAR(50) DEFAULT NULL,
                    `price` TEXT DEFAULT NULL,
                    `old_price` TEXT DEFAULT NULL,
                    `new_price` TEXT DEFAULT NULL,
                    `category_id` INT
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
                $this->connection->exec($tbl_cosmetics);
            }
        }
        catch (\PDOException $e)
        {
            date_default_timezone_set('Asia/Yekaterinburg');
            $err = error_log("[". date('Y-m-d H:i:s') ."] DB Error: {$e->getMessage()}". PHP_EOL, 3, ERROR_LOGS);
            echo "<p style='color:white'>b</p>";
            Application::$app->abort->error(500);
            file_put_contents(ERROR_LOGS, $err, FILE_APPEND);
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
            $err = error_log("[". date('Y-m-d H:i:s') ."] {$e->getMessage()}". PHP_EOL, 3, ERROR_LOGS);
            file_put_contents(ERROR_LOGS, $err, FILE_APPEND);
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
        $auth_token = strval(bin2hex(random_bytes(32)));
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

        setcookie('~23', $hash_token, $options);
        return $auth_token;

    }
    
    public function user_back()
    {
        if (! isset($_SESSION['name']) && isset($_COOKIE['~23'])) {

            $hash_token = $_COOKIE['~23'];

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
