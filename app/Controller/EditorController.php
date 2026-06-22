<?php
namespace App\Controller;

use App\Helper\Text\Text;
use App\Helper\Image\Image as ImageHelper;
use RuntimeException;
use Exception;
use finfo;
use PDO;

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

            $slug = isset($_POST['slug']) ? trim($_POST['slug']) : 'new';
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
                if (!isset($map[$mime])) {
                    throw new RuntimeException('Недопустимый тип файла');
                }
                $ext = $map[$mime];
                $fileNameTmp = $outer_id . '.' . $ext;
                $target = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileNameTmp;
                if (!move_uploaded_file($file['tmp_name'], $target)) {
                    throw new RuntimeException('Не удалось сохранить файл во временную папку');
                }
                // Загружаем исходник в GdImage по расширению
                $srcImg = null;
                switch ($ext) {
                    case 'jpg':
                        $srcImg = @imagecreatefromjpeg($target);
                        break;
                    case 'png':
                        $srcImg = @imagecreatefrompng($target);
                        break;
                    case 'webp':
                        $srcImg = @imagecreatefromwebp($target);
                        break;
                    default:
                        if (file_exists($target)) @unlink($target);
                        throw new RuntimeException('Неподдерживаемое расширение изображения');
                }
                if ($srcImg === false || $srcImg === null) {
                    if (file_exists($target)) @unlink($target);
                    throw new RuntimeException('Не удалось открыть загруженное изображение');
                }

                $imagePublicUrl = __DIR__ ."/../../public/images/{$slug}/{$outer_id}";
                //$imagePath = __DIR__ ."/../../public/images/new/{$outer_id}";
                $imageHelper = new ImageHelper();
                $newImage = $imageHelper->changeImg($srcImg, $imagePublicUrl);
                if (!$newImage) {
                    imagedestroy($srcImg);
                    throw new RuntimeException('Обработка изображения не удалась');
                }
                // удаляем исходный временный файл из uploadDir(/public/temporary)
                if (file_exists($target)) @unlink($target);

                $imageDbPath = "/images/{$slug}/{$outer_id}.webp";
            
            }

            // DB
            $pdo = db();
            if (!$pdo) {
                throw new RuntimeException('DB not connection');
            }
            $pdo->beginTransaction();
            // новый продукт
            if ($outer_id === 'new') {
                $new_outer = 'ART-' . time();
                $sql = "INSERT INTO products (
                    outer_id, title, price, old_price, new_price, description, image, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW()
                )";
                $img = $imageDbPath ?? null;
                db()->query($sql, [$new_outer, $title, $price, $old_price, $new_price, $description, $img]);
                $savedOuter = $new_outer;
            // редактируемый продукт
            } else {
                if (isset($newImage)) {
                    $sql = "UPDATE " . TABLE_NAME . 
                        " SET title = ?, price = ?, old_price = ?, new_price = ?,
                        description = ?, image = ? WHERE outer_id = ?";
                    db()->query($sql, [$title, $price, $old_price, $new_price, 
                        $description, $imageDbPath, $outer_id]);
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
            if ($imageDbPath) $response['image'] = $imageDbPath;

        } catch (Exception $e) {
            if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) $pdo->rollBack();
            $response['error'] = $e->getMessage();
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        //cache()->refreshCache();

    }


}
