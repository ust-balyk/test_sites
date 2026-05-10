<?php
// Подключаем ваш конфиг БД и класс безопасности
//require_once '../config.php'; 
require_once '../app/Helper/Text/Text.php'; 

header('Content-Type: application/json');

$response = ['success' => false, 'error' => ''];

// Проверка прав (обязательно!)
/*
if (empty($_SESSION['is_admin'])) {
    echo json_encode(['success' => false, 'error' => 'Доступ запрещен']);
    exit;
}*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $outer_id = $_POST['outer_id'] ?? 'new';
        $title = strip_tags(trim($_POST['title']));
        $price = $_POST['price'];
        
        // 🛡️ ОЧИСТКА: используем ваш метод с HTMLPurifier
        $description = Text::clean($_POST['description']);

        // 1. ОБРАБОТКА КАРТИНКИ
        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = '../uploads/products/';
            
            // Создаем папку, если её нет
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);

            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fileName = strtolower($outer_id) . '_' . time() . '.' . $extension;
            $targetFile = $uploadDir . $fileName;

            // Проверка типа файла
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array(strtolower($extension), $allowed)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $imagePath = '../puplic/images/' . $fileName;
                }
            }
        }

        // 2. СОХРАНЕНИЕ В БАЗУ ДАННЫХ
        if ($outer_id === 'new') {
            // ЛОГИКА: Добавление нового товара
            $new_id = "ART-" . time(); // временный генератор артикула
            $sql = "INSERT INTO products (outer_id, title, price, description, image) VALUES (?, ?, ?, ?, ?)";
            $stmt = db()->prepare($sql);
            $stmt->execute([$new_id, $title, $price, $description, $imagePath ?? '/images/no-image.png']);
        } else {
            // ЛОГИКА: Обновление текущего
            if ($imagePath) {
                $sql = "UPDATE ". TABLE_NAME .
                   " SET title = ?, price = ?, description = ?, image = ? WHERE outer_id = ?";
                $stmt = db()->prepare($sql);
                $stmt->execute([$title, $price, $description, $imagePath, $outer_id]);
            } else {
                $sql = "UPDATE ". TABLE_NAME .
                   " products SET title = ?, price = ?, description = ? WHERE outer_id = ?";
                $stmt = db()->prepare($sql);
                $stmt->execute([$title, $price, $description, $outer_id]);
            }
        }

        // 3. СБРОС КЕША (если вы его используете)
        //refresh_product_cache($outer_id);

        $response['success'] = true;

    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
    }
}

echo json_encode($response);
