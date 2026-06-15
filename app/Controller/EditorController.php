<?php
namespace App\Controller;
use App\Helper\Text\Text;
use finfo;

class EditorController
{
    static function index()
    {
        // Принимает multipart/form-data: outer_id, title, price, description, image(file), reviews(JSON)
        // 
        header('Content-Type: application/json; charset=utf-8');

        $response = ['success' => false, 'error' => ''];

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new RuntimeException('Неверный метод запроса');
            }

            // не мастер (включая неавторизованных)
            if (!session()->get('user.role') || session()->get('user.role') !== 'master') {
                throw new RuntimeException('Доступ запрещен'); 
            }

            $outer_id = isset($_POST['outer_id']) ? trim($_POST['outer_id']) : 'new';
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';

            $price = isset($_POST['price']) ? trim($_POST['price']) : '';
            $old_price = '';
            $new_price = '';          
            if (preg_match_all('/\d[\d\s\.,\-]*руб/iuu', $price, $m) === 2) {
                $old_price = $m[0][1];
                $new_price = $m[0][0];
                $price = '';
            } else {
                $old_price = '';
                $new_price = '';
            }

            $descriptionRaw = isset($_POST['description']) ? $_POST['description'] : '';
            $reviewsJson = isset($_POST['reviews']) ? $_POST['reviews'] : null;

            /*-------------------*/

            if ($title === '') {
                throw new RuntimeException('Пустое название товара');
            }

            // Очистка/санитайз описания
            $description = Text::clean($descriptionRaw);

            // Файловая обработка
            $uploadDir = realpath(__DIR__ .'/../../public/temporary') 
                ?: (__DIR__ .'/../../public/temporary');
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
                $imagePublicPath = __DIR__ .'/../../images/'. $fileName;
            }

            // DB
            $pdo = db(); // ваша функция db() должна вернуть PDO
            $pdo->beginTransaction();
            // новый продукт
            if ($outer_id === 'new') {
                $new_outer = 'ART-' . time();
                $sql = "INSERT INTO products (
                    outer_id, title, price, old_price, new_price, description, image, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW()
                )";
                $img = $imagePublicPath ?? '/images/no-image.png';
                db()->query($sql, [$new_outer, $title, $price, $old_price, $new_price, $description, $img]);
                $savedOuter = $new_outer;
            // редактируемый продукт
            } else {
                if ($imagePublicPath) {
                    $sql = "UPDATE " . TABLE_NAME . 
                        " SET title = ?, price = ?, old_price = ?, new_price = ?,
                        description = ?, image = ? WHERE outer_id = ?";
                    db()->query($sql, [$title, $price, $old_price, $new_price, 
                        $description, $imagePublicPath, $outer_id]);
                } else {
                    $sql = "UPDATE " . TABLE_NAME . 
                        " SET title = ?, price = ?, old_price = ?, new_price = ?,
                        description = ? WHERE outer_id = ?";
                    db()->query($sql, [$title, $price, $old_price, $new_price, $description, $outer_id]);
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

        cache()->refreshCache();


    }


}
