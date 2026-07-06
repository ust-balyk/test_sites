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
        header('Content-Type: application/json; charset=utf-8');

        $response = ['success' => false, 'error' => ''];

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new RuntimeException('Неверный метод запроса');
            }

            $role = session()->get('user.role');
            if ($role !== 'master' && $role !== 'assistant') {
                throw new RuntimeException('Доступ запрещен');
            }

            // 1. ПОЛУЧЕНИЕ ДАННЫХ
            $slug      = isset($_POST['slug']) ? trim($_POST['slug']) : 'new';
            $outer_id  = isset($_POST['outer_id']) ? trim($_POST['outer_id']) : 'new';
            $category  = isset($_POST['category']) ? trim($_POST['category']) : ''; // Получаем категорию из селекта
            $title     = isset($_POST['title']) ? trim($_POST['title']) : '';
            $price     = isset($_POST['price']) ? trim($_POST['price']) : '';
            $descriptionRaw = isset($_POST['description']) ? $_POST['description'] : '';
            $reviewsJson = isset($_POST['reviews']) ? $_POST['reviews'] : null;

            // Определение окончательного ID товара (ВАЖНО: делаем это ДО обработки фото)
            if ($outer_id === 'new') {
                $final_id = 'ART-' . time();
            } else {
                $final_id = $outer_id;
            }

            // Определение папки для сохранения изображения
            // Если это новый товар и указана категория — используем её. 
            // Иначе используем slug (для редактирования старых товаров)
            /*
            $folder = 'general'; 
            if ($outer_id === 'new' && !empty($category)) {
                $folder = $slug; //$folder = $category;
            } elseif (!empty($slug) && $slug !== 'new') {
                $folder = $slug;
            }
            */
            $folder_img = $slug;

            // Обработка цены
            $old_price = '';
            $new_price = '';
            if (preg_match_all('/\d[\d\s\.,\-]*руб/iuu', $price, $m) === 2) {
                $old_price = $m[0][1];
                $new_price = $m[0][0];
                $price = '';
            }

            $description = Text::clean($descriptionRaw);

            // 2. РАБОТА С ИЗОБРАЖЕНИЕМ
            $uploadDir = realpath(__DIR__ .'/../../public/temporary')
                ?: (__DIR__ .'/../../public/temporary');
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
                    throw new RuntimeException('Не удалось создать каталог для загрузки');
                }
            }

            $imageDbPath = null;

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
                // Используем финальный ID для временного имени, чтобы избежать конфликтов
                $fileNameTmp = $final_id . '_' . time() . '.' . $ext;
                $target = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileNameTmp;

                if (!move_uploaded_file($file['tmp_name'], $target)) {
                    throw new RuntimeException('Не удалось сохранить файл во временную папку');
                }

                $srcImg = null;
                switch ($ext) {
                    case 'jpg':  $srcImg = @imagecreatefromjpeg($target); break;
                    case 'png':  $srcImg = @imagecreatefrompng($target); break;
                    case 'webp': $srcImg = @imagecreatefromwebp($target); break;
                }

                if ($srcImg === false || $srcImg === null) {
                    if (file_exists($target)) @unlink($target);
                    throw new RuntimeException('Не удалось открыть загруженное изображение');
                }

                // ПУТЬ СОХРАНЕНИЯ: public/images/<category-folder>/<final_id>
                // ImageHelper сам создаст папку, если она не существует (обычно реализовано внутри changeImg)
                $imagePublicUrl = __DIR__ ."/../../public/images/{$folder_img}/{$final_id}";

                $imageHelper = new ImageHelper();
                $newImage = $imageHelper->changeImg($srcImg, $imagePublicUrl);

                if (!$newImage) {
                    imagedestroy($srcImg);
                    throw new RuntimeException('Обработка изображения не удалась');
                }

                if (file_exists($target)) @unlink($target);

                // Путь, который пойдет в базу данных
                $imageDbPath = "/images/{$folder_img}/{$final_id}.webp";
            }

            // 3. РАБОТА С БАЗОЙ ДАННЫХ
            $pdo = db();
            if (!$pdo) {
                throw new RuntimeException('DB not connection');
            }

            $pdo->beginTransaction();

            if ($outer_id === 'new') {
                // СОЗДАНИЕ НОВОГО ТОВАРА
                $sql = "INSERT INTO products (
                    outer_id, title, price, old_price, new_price, description, image
                ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

                $img = $imageDbPath ?? null;

                db()->query($sql, [
                    $final_id, $title, $price, $old_price, $new_price, $description, $img
                ]);

                $savedOuter = $final_id;
            } else {
                // ОБНОВЛЕНИЕ СУЩЕСТВУЮЩЕГО
                if (isset($imageDbPath)) {
                    $sql = "UPDATE " . TABLE_NAME . "
                        SET title = ?, price = ?, old_price = ?, new_price = ?,
                            description = ?, image = ? WHERE outer_id = ?";
                    db()->query($sql, [
                        $title, $price, $old_price, $new_price,
                        $description, $imageDbPath, $outer_id
                    ]);
                } else {
                    $sql = "UPDATE " . TABLE_NAME . "
                        SET title = ?, price = ?, old_price = ?, new_price = ?,
                            description = ? WHERE outer_id = ?";
                    db()->query($sql, [
                        $title, $price, $old_price, $new_price,
                        $description, $outer_id
                    ]);
                }

                $savedOuter = $outer_id;
            }

            $pdo->commit();

            $response['success'] = true;
            $response['outer_id'] = $savedOuter;
            if ($imageDbPath) $response['image'] = $imageDbPath;

        } catch (Exception $e) {
            if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $response['error'] = $e->getMessage();
        }

        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        cache()->refreshCache();
    }
}

