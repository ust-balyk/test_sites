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

            $dbname = 'japan_in_ru';

            $sql = "SELECT EXISTS (
                        SELECT 1 
                        FROM INFORMATION_SCHEMA.SCHEMATA 
                        WHERE SCHEMA_NAME = :dbname
                    ) AS db_exists";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute(['dbname' => $dbname]);

            $exists = (bool)$stmt->fetchColumn();

            if ($exists == false) {

                $japan_in_ru = "CREATE DATABASE `japan_in_ru`
                                CHARACTER SET utf8mb4
                                COLLATE utf8mb4_unicode_ci;";
            
                if ($this->connection->exec($japan_in_ru)) {

                    $this->connection->exec("USE japan_in_ru");

                    //frend — пользователь, assistant — модератор
                    $tbl_users = "START TRANSACTION;
                    CREATE TABLE `users` (
                        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        `name` VARCHAR(55) NULL DEFAULT NULL,
                        `email` VARCHAR(105) UNIQUE NOT NULL,
                        `password` VARCHAR(255) NOT NULL,
                        `role` VARCHAR(10) DEFAULT 'frend',
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        `auth_token` VARCHAR(255) DEFAULT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                    COMMIT;";
                    $this->connection->exec($tbl_users);

                    /* стартовый пакет */
                    $tbl_start = "START TRANSACTION;
                    CREATE TABLE `start` (
                        `id` INT PRIMARY KEY,
                        `category` VARCHAR(55) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `slug` VARCHAR(55) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE KEY,
                        `parent_id` INT NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                        INSERT IGNORE INTO start (`id`, `category`, `slug`, `parent_id`) VALUES
                        (1, 'декоративная косметика', 'makeup', 0),
                        (2, 'для лица', 'for-face', 0),
                        (3, 'для полости рта', 'for-oral-cavity', 0),
                        (4, 'для волос', 'for-hair', 0),
                        (5, 'для тела', 'for-body', 0),
                        (6, 'для рук', 'for-hands', 0),
                        (7, 'для ног', 'for-feet', 0),
                        (8, 'ароматерапия', 'aromatherapy', 0),
                        (9, 'подарочные наборы', 'gift-set', 0),
                        (10, 'аксессуары', 'accessories', 0);
                    COMMIT;";
                    $this->connection->exec($tbl_start);

                    $tbl_cosmetics = "START TRANSACTION;
                    CREATE TABLE `cosmetics` (
                        `id` INT AUTO_INCREMENT PRIMARY KEY,
                        `category` TEXT NOT NULL,
                        `slug` VARCHAR(50) NOT NULL,
                        `outer_id` INT NOT NULL UNIQUE KEY,
                        `title` TEXT NOT NULL,
                        `description` TEXT DEFAULT NULL,
                        `image` VARCHAR(50) DEFAULT NULL,
                        `price` TEXT DEFAULT NULL,
                        `old_price` TEXT DEFAULT NULL,
                        `new_price` TEXT DEFAULT NULL,
                        `category_id` INT,
                        `in_stock` INT NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                    ALTER TABLE `cosmetics` ADD FOREIGN KEY (`category_id`) 
                    REFERENCES `start`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
                    COMMIT;";
                    $this->connection->exec($tbl_cosmetics);

                    /* основной пакет */
                    $tbl_categories = "START TRANSACTION;
                    CREATE TABLE `categories` (
                        `id` INT PRIMARY KEY,
                        `category` VARCHAR(55) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `slug` VARCHAR(55) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE KEY,
                        `parent_id` INT NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                        INSERT IGNORE INTO categories (`id`, `category`, `slug`, `parent_id`) VALUES
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
                        (12, 'Зоотовары', 'pet-supplies', 0);
                    COMMIT;";
                    $this->connection->exec($tbl_categories); 

                    $tbl_products = "START TRANSACTION;
                    CREATE TABLE `products` (
                        `id` INT AUTO_INCREMENT PRIMARY KEY,
                        `category` TEXT NOT NULL,
                        `slug` VARCHAR(50) NOT NULL,
                        `outer_id` INT NOT NULL UNIQUE KEY,
                        `title` TEXT NOT NULL,
                        `description` TEXT DEFAULT NULL,
                        `image` VARCHAR(50) DEFAULT NULL,
                        `price` TEXT DEFAULT NULL,
                        `old_price` TEXT DEFAULT NULL,
                        `new_price` TEXT DEFAULT NULL,
                        `category_id` INT,
                        `in_stock` INT NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                    ALTER TABLE `products` ADD FOREIGN KEY (`category_id`) 
                    REFERENCES `categories`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
                    COMMIT;";
                    $this->connection->exec($tbl_products);

                    //Основная информация о заказе
                    $tbl_orders = "START TRANSACTION;
                    CREATE TABLE orders (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        total_price INT NOT NULL,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
                    COMMIT;";
                    $this->connection->exec($tbl_orders);

                    // Состав заказа (какие именно товары куплены)
                    $tbl_order_items = "START TRANSACTION;
                    CREATE TABLE order_items (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        order_id INT NOT NULL,
                        product_id INT NOT NULL,
                        quantity INT NOT NULL,
                        price_at_purchase INT NOT NULL,
                    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE);
                    COMMIT;";
                    $this->connection->exec($tbl_order_items);
                    /* price_at_purchase INT NOT NULL, -- цена на момент покупки */

                    $relations = "
                    ALTER TABLE `cosmetics`
                    ADD CONSTRAINT `fk_cosmetics_to_start` 
                    FOREIGN KEY (`category_id`) REFERENCES `start` (`id`) 
                    ON DELETE RESTRICT ON UPDATE RESTRICT;
        
                    ALTER TABLE `products`
                    ADD CONSTRAINT `fk_products_to_categories` 
                    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) 
                    ON DELETE RESTRICT ON UPDATE RESTRICT;";
                    $this->connection->exec($relations);
                }

                $this->createMasterUser();

            } else {

                $this->connection->exec("USE japan_in_ru");

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

    function createMasterUser() {

        $name = NAME;
        $email = EMAIL;
        $password = PASSWORD;

        // Подбор оптимальной сложности (Cost)
        $timeTarget = 2; // секунда 
        $cost = 10;
    
        do {
            $cost++;
            $start = microtime(true);
            password_hash($password, PASSWORD_BCRYPT, ["cost" => $cost]);
            $end = microtime(true);
        
            if ($cost >= 15) break; //максимум 31 (рекомендуемое 10-13) 
        
        } while (($end - $start) < $timeTarget);

        $finalCost = $cost - 1;

        // Хеширование и вставка
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT, ['cost' => $finalCost]);

        $sql = "INSERT INTO users (name,email,password,role) VALUES (:name,:email,:password,:role)";
        $stmt = $this->connection->prepare($sql);
        
        try {
            $stmt->execute([
                ':name'     => $name,
                ':email'    => $email,
                ':password' => $hashedPassword,
                ':role'     => 'master'
            ]);
            return true;

        } catch (PDOException $e) {
            return $e->getMessage();
        
        }
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
            $err = error_log("[". date('Y-m-d * H:i:s') ."] {$e->getMessage()}". PHP_EOL, 3, ERROR_LOGS);
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

    public function countDB_ByCategory(string $tbl, string $slug): ?int
    {
        $total_count = $this->query(
            "SELECT COUNT(*) AS total_count FROM `{$tbl}` WHERE `slug` = :value", ['value' => $slug]
        )->getOne();
        if ($total_count === null) return null;

        $count = (int) ($total_count['total_count'] ?? 0);
        return $count === 0 ? null : $count;

    }

    public function getOne()
    {
        return $this->stmt->fetch();
    
    }

    public function getColumn($tbl) // получить количество записей
    {
        $this->query("SELECT COUNT(*) FROM {$tbl}");
        return (int)$this->stmt->fetchColumn();
    
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

    
    /* авторизация cookie */   
    public function generate_token_23()
    {
        $auth_token = strval(bin2hex(random_bytes(64)));
        return $auth_token;

    }

    public function set_token_23($user_id = 0) // последнее ID пользователя или получаеи при входе
    {

        $auth_token = $this->generate_token_23();
        $hash_token = password_hash($auth_token, PASSWORD_DEFAULT);

        // Сохраняем ХЕШ в базу данных для конкретного пользователя
        $this->query('UPDATE `users` SET `auth_token` = :hash_token WHERE `id` = :id',
            [ 'hash_token' => $hash_token, 'id' => $user_id ]);

        $options = [
            'expires'  => time() + 31536000,
            'path'     => '/',
            'secure'   => true, //для тестов (true - для боя),
            'httponly' => true,
            'samesite' => 'Strict', //'Lax', //для тестов ('Strict'-для боя)
        ];

        $value = "{$user_id}:{$auth_token}"; // ID и токен внутри одной строки
        setcookie("~23", $value, $options);
        
    }

    public function user_back()
    {
        
        $token = $_COOKIE['~23'] ?? null;

        if ($token) {

            // 2 — это лимит. Она говорит PHP: «Раздели строку максимум на две части»
            [$user_id, $hash_token] = explode(':', $token, 2);
            $user_id = (int)$user_id;

            // зайти как админ можно только через пароль ( <> 1 )
            $user = $this->query('SELECT * FROM `users` WHERE `id` = :id AND id <> 1; LIMIT 1',
                ['id' => $user_id])->getOne();

            if ($user && password_verify($hash_token, $user['auth_token'])) {
                Application::$app->session->set('csrf_token', bin2hex(random_bytes(32)));
                Application::$app->session->set('user.email', $user['email']);
                Application::$app->session->set('user.name', $user['name']);
                Application::$app->session->set('user.role', $user['role']);
                $this->set_token_23($user_id); // обновляем данные

            }
            return false;

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

    public function getLastId(): false|string
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
