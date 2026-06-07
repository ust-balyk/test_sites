<?php
namespace App\Controller;
use App\Helper\Text\Text;

class EditorController
{
    static function index()
    {
        // Принимает multipart/form-data: outer_id, title, price, description, image(file), reviews(JSON)
        // Настройте пути, TABLE_NAME и функцию db()

        //require_once __DIR__ . '/../app/Helper/Text/Text.php';
        header('Content-Type: application/json; charset=utf-8');

        $response = ['success' => false, 'error' => ''];

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new RuntimeException('Неверный метод запроса');
            }

            // Проверка прав — адаптируйте под проект
            //session_start();
            // if (empty($_SESSION['is_admin'])) { throw new RuntimeException('Доступ запрещен'); }

            $outer_id = isset($_POST['outer_id']) ? trim($_POST['outer_id']) : 'new';
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $price = isset($_POST['price']) ? trim($_POST['price']) : '';
            $descriptionRaw = isset($_POST['description']) ? $_POST['description'] : '';
            $reviewsJson = isset($_POST['reviews']) ? $_POST['reviews'] : null;

            if ($title === '') {
                throw new RuntimeException('Пустое название товара');
            }

            // Очистка/санитайз описания
            $description = Text::clean($descriptionRaw);

            // Файловая обработка
            $uploadDir = realpath(__DIR__ . '/../uploads/products') ?: (__DIR__ . '/../uploads/products');
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
                    throw new RuntimeException('Не удалось создать каталог для загрузки');
                }
            }

            $imagePublicPath = null;
            if (!empty($_FILES['image']['name'])) {
                $file = $_FILES['image'];
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    throw new RuntimeException('Ошибка загрузки файла: ' . $file['error']);
                }
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($file['tmp_name']);
                $map = ['image/jpeg'=>'jpg','image/pjpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
                if (!isset($map[$mime])) throw new RuntimeException('Недопустимый тип файла');
                $ext = $map[$mime];
                $base = ($outer_id === 'new') ? 'new' : preg_replace('/[^a-z0-9_-]/i','_', $outer_id);
                $fileName = $base . '_' . time() . '.' . $ext;
                $target = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;
                if (!move_uploaded_file($file['tmp_name'], $target)) 
                    throw new RuntimeException('Не удалось сохранить файл');
                $imagePublicPath = '/uploads/products/' . $fileName;
            }

            // DB
            $pdo = db(); // ваша функция db() должна вернуть PDO
            $pdo->beginTransaction();

            if ($outer_id === 'new') {
                $new_outer = 'ART-' . time();
                $sql = "INSERT INTO products (
                    outer_id, title, price, description, image, created_at) VALUES (?, ?, ?, ?, ?, NOW()
                )";
                //$stmt = $pdo->prepare($sql);
                $img = $imagePublicPath ?? '/images/no-image.png';
                //$stmt->execute([$new_outer, $title, $price, $description, $img]);
                db()->query($sql, [$new_outer, $title, $price, $description, $img]);
                $savedOuter = $new_outer;
            } else {
                if ($imagePublicPath) {
                    $sql = "UPDATE " . TABLE_NAME . 
                        " SET title = ?, price = ?, description = ?, image = ? WHERE outer_id = ?";
                    //$stmt = $pdo->prepare($sql);
                    //$stmt->execute([$title, $price, $description, $imagePublicPath, $outer_id]);
                    db()->query($sql, [$title, $price, $description, $imagePublicPath, $outer_id]);
                } else {
                    $sql = "UPDATE " . TABLE_NAME . " SET title = ?, price = ?, description = ? WHERE outer_id = ?";
                    //$stmt = $pdo->prepare($sql);
                    //$stmt->execute([$title, $price, $description, $outer_id]);
                    db()->query($sql, [$title, $price, $description, $outer_id]);
                }
                $savedOuter = $outer_id;
            }

            /*
            // Сохранение отзывов (опционально) — в отдельной таблице или JSON-колонке
            if ($reviewsJson) {
                $reviews = json_decode($reviewsJson, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($reviews)) {
                    // пример: сохраняем в колонку reviews_json в products (если есть)
                    $sql = "UPDATE " . TABLE_NAME . " SET reviews_json = ? WHERE outer_id = ?";
                    //$stmt = $pdo->prepare($sql);
                    //$stmt->execute([json_encode($reviews, JSON_UNESCAPED_UNICODE), $savedOuter]);
                    db()->query($sql, [json_encode($reviews, JSON_UNESCAPED_UNICODE), $savedOuter]);
                }
            }*/

            $pdo->commit();

            $response['success'] = true;
            $response['outer_id'] = $savedOuter;
            if ($imagePublicPath) $response['image'] = $imagePublicPath;

            // можно вернуть redirect: '/cosmetics/' . $slug
        } catch (Exception $e) {
            if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) $pdo->rollBack();
            $response['error'] = $e->getMessage();
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);


    }


}
