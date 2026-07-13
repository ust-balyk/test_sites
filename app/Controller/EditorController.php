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

        $pdo = db();
        if (!$pdo) {
            throw new RuntimeException('DB not connection');
        }

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new RuntimeException('Неверный метод запроса');
            }

            $role = session()->get('user.role');
            if ($role !== 'master' && $role !== 'assistant') {
                throw new RuntimeException('Доступ запрещен');
            }

            $mode      = isset($_POST['mode']) ? trim($_POST['mode']) : '';
            
            $slug      = isset($_POST['slug']) ? trim($_POST['slug']) : '';
            
            $outer_id  = isset($_POST['outer_id']) ? trim($_POST['outer_id']) : '';
            if ($outer_id === '') $outer_id = generateId($pdo);

            $title     = isset($_POST['title']) ? trim($_POST['title']) : '';
            
            $price     = isset($_POST['price']) ? trim($_POST['price']) : '';
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

            //$descriptionRaw = isset($_POST['description']) ? $_POST['description'] : '';
            //$description = Text::clean($descriptionRaw);

            $description = isset($_POST['description']) ? $_POST['description'] : '';

            //$reviewsJson = isset($_POST['reviews']) ? $_POST['reviews'] : null;

            $uploadDir = realpath(__DIR__ .'/../../public/temporary')
                ?: (__DIR__ .'/../../public/temporary');
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
                    throw new RuntimeException('Не удалось создать каталог для загрузки');
                }
            }
 
            $imageDbPath = '';
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

                // Путь в public/images/<category-slug>/<outer_id>
                $imagePublicUrl = __DIR__ ."/../../public/images/{$slug}/{$outer_id}";

                $imageHelper = new ImageHelper();
                $newImage = $imageHelper->changeImg($srcImg, $imagePublicUrl);

                if (!$newImage) {
                    imagedestroy($srcImg);
                    throw new RuntimeException('Обработка изображения не удалась');
                }

                if (file_exists($target)) @unlink($target);

                $imageDbPath = "/images/{$slug}/{$outer_id}.webp" ?: '';

            }

            // ====================================================== //

            $pdo->beginTransaction();

            if ($mode === 'delete') {
                if ($outer_id == '') throw new RuntimeException('outer_id обязателен для delete');
                if ($slug == '') throw new RuntimeException('slug обязателен для delete');
            
                $publicRoot = realpath(__DIR__ . '/../../public');
                if ($publicRoot === false) throw new RuntimeException('Не удалось определить директорию public');
            
                $imgFilePath = $publicRoot . "/images/{$slug}/{$outer_id}.webp";
            
                if (is_file($imgFilePath)) {
                    if (!unlink($imgFilePath)) {
                        throw new RuntimeException('Не удалось удалить файл изображения');
                    }
                }
            
                $pdo->query("DELETE FROM " . TABLE_NAME . " WHERE outer_id = ?", [$outer_id]);

            } elseif ($mode === 'add') {

                $arr_cat_id = [
                    1 => 'makeup',
                    2 => 'for-face',
                    3 => 'for-oral-cavity',
                    4 => 'for-hair',
                    5 => 'for-body',
                    6 => 'for-hands',
                    7 => 'for-feet',
                    8 => 'aromatherapy',
                    9 => 'gift-set',
                    10 =>'accessories',
                ];
                $category_id = array_search($slug, $arr_cat_id, true); // true — строгое сравнение
                if ($category_id === false) {
                    $response = ['success' => false, 'error' => 'invalid category slug'];
                }

                $arr_cat = [
                    'декоративная косметика' => 'makeup',
                    'для лица'               => 'for-face',
                    'для полости рта'        => 'for-oral-cavity',
                    'для волос'              => 'for-hair',
                    'для тела'               => 'for-body',
                    'для рук'                => 'for-hands',
                    'для ног'                => 'for-feet',
                    'ароматерапия'           => 'aromatherapy',
                    'подарочные наборы'      => 'gift-set',
                    'аксессуары'             => 'accessories',
                ];
                $category = array_search($slug, $arr_cat, true); // true — строгое сравнение
                if ($category === false) {
                    $response = ['success' => false, 'error' => 'invalid category category'];
                }
                
                $in_stock = 1;

                $sql = "INSERT INTO " . TABLE_NAME . " (
                    outer_id, slug, category, title, price, old_price, new_price, description, image, category_id, in_stock
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $pdo->query($sql, [
                    $outer_id, $slug, $category, $title, $price, $old_price, $new_price, $description, $imageDbPath, $category_id, $in_stock
                ]);

            } elseif ($mode === 'edit') {

                if (empty($imageDbPath)) {
                    $sql = "UPDATE " . TABLE_NAME . "
                        SET title = ?, price = ?, old_price = ?, new_price = ?, description = ? WHERE outer_id = ?";
                    $pdo->query($sql, [
                        $title, $price, $old_price, $new_price, $description, $outer_id
                    ]);
                
                } else {
                    $sql = "UPDATE " . TABLE_NAME . "
                        SET title = ?, price = ?, old_price = ?, new_price = ?, description = ?, image = ? WHERE outer_id = ?";
                    $pdo->query($sql, [
                        $title, $price, $old_price, $new_price, $description, $imageDbPath, $outer_id
                    ]);
                
                }
            } else {
                throw new RuntimeException('Неверный режим');
            }

            $pdo->commit();

            $response['success'] = true;
            $response['outer_id'] = $outer_id;
            if ($imageDbPath) $response['image'] = $imageDbPath;

        } catch (Exception $e) {
            if ($pdo instanceof PDO && $pdo->inTransaction()) {
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

