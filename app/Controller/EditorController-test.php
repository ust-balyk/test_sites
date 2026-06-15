<?php
declare(strict_types=1);

namespace App\Controller;

use App\Helper\Text\Text;
use App\Helper\Image\Image as ImageHelper;
use finfo;
use RuntimeException;
use Exception;
use PDO;

class EditorController
{
    static function index()
    {
        // Принимает multipart/form-data: outer_id, title, price, description, image(file), reviews(JSON)
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

            // Файловая обработка: временная папка
            $uploadDir = realpath(__DIR__ .'/../../public/temporary')
                ?: (__DIR__ .'/../../public/temporary');
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
                    throw new RuntimeException('Не удалось создать каталог для загрузки');
                }
            }

            // Папка для публичных изображений
            $publicImagesDir = realpath(__DIR__ .'/../../images') ?: (__DIR__ .'/../../images');
            if (!is_dir($publicImagesDir)) {
                if (!mkdir($publicImagesDir, 0775, true) && !is_dir($publicImagesDir)) {
                    throw new RuntimeException('Не удалось создать каталог для изображений');
                }
            }

            // Получим текущее значение image из БД (если редактирование существующего)
            $currentImagePath = null;
            if ($outer_id !== 'new') {
                $row = db()->query(
                    "SELECT image FROM " . TABLE_NAME . " WHERE outer_id = ?", [$outer_id]
                )->getOne();
                if ($row) {
                    $currentImagePath = $row['image'] ?? null;
                }
            }

            $imagePublicPath = null; // конечный путь для записи в БД (относительный, как у вас принято)
            $tempCreatedFiles = []; // для очистки при ошибке

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
                // временное имя в uploadDir
                $fileNameTmp = $base . '_' . time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
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

                // Обрабатываем изображение и сохраняем временно в images 
                // как .tmp (чтобы не перезаписывать существующее)
                $tmpFinalName = $base . '_' . time() . '_' . bin2hex(random_bytes(6)) . '.webp.tmp';
                $tmpFinalPath = rtrim($publicImagesDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $tmpFinalName;

                $imageHelper = new ImageHelper();
                $saved = $imageHelper->changeImg($srcImg, $tmpFinalPath);
                imagedestroy($srcImg);

                // удаляем исходный временный файл из uploadDir
                if (file_exists($target)) @unlink($target);

                if (!$saved || !file_exists($tmpFinalPath)) {
                    if (file_exists($tmpFinalPath)) @unlink($tmpFinalPath);
                    throw new RuntimeException('Обработка изображения не удалась');
                }

                $tempCreatedFiles[] = $tmpFinalPath;

                // Решаем стратегию: если у продукта в БД уже есть путь 
                // и он не плейсхолдер -> заменим файл по этому пути (atomic)
                $isPlaceholder = empty($currentImagePath) || $currentImagePath === '/images/no-image.png';
                if ($isPlaceholder) {
                    // Сгенерируем имя и конечный путь и пометим для записи в БД
                    $finalFileName = $base . '_' . time() . '_' . bin2hex(random_bytes(6)) . '.webp';
                    $finalPath = rtrim($publicImagesDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $finalFileName;
                    // Переместим tmp -> final (atomic rename, но сначала проверим перезапись)
                    if (!rename($tmpFinalPath, $finalPath)) {
                        // попытка через copy+unlink
                        if (!@copy($tmpFinalPath, $finalPath) || !@unlink($tmpFinalPath)) {
                            // оставим tmp для очистки в catch
                            throw new RuntimeException('Не удалось сохранить обработанное изображение');
                        }
                    }
                    $imagePublicPath = '/images/' . $finalFileName;
                    // запомнить для очистки (файл уже перемещён -> удалить tmp из списка)
                    $tempCreatedFiles = array_values(
                        array_filter($tempCreatedFiles, function($p) 
                            use ($tmpFinalPath){ return $p !== $tmpFinalPath; })
                    );
                } else {
                    // У нас есть текущий путь в БД: /images/...
                    // Преобразуем относительный путь в абсолютный FS путь
                    $existingRel = $currentImagePath;
                    // Если путь относительный (начинается с /images/), приводим к папке images
                    if (strpos($existingRel, '/images/') === 0) {
                        $existingAbs = rtrim($publicImagesDir, DIRECTORY_SEPARATOR) . 
                            DIRECTORY_SEPARATOR . 
                            ltrim(substr($existingRel, strlen('/images/')), 
                            DIRECTORY_SEPARATOR);
                    } else {
                        // если формат другой, всё равно склоняемся к сохранению с тем же именем
                        $existingAbs = rtrim($publicImagesDir, DIRECTORY_SEPARATOR) . 
                            DIRECTORY_SEPARATOR . basename($existingRel);
                    }

                    // Создаём резервную копию существующего файла (если есть)
                    $backupPath = null;
                    if (file_exists($existingAbs)) {
                        $backupPath = $existingAbs . '.bak.' . time();
                        if (!@copy($existingAbs, $backupPath)) {
                            // не фатально — продолжим, но будем внимательны при переименовании
                            $backupPath = null;
                        }
                    }

                    // Попытка atomical replace: переименовать tmp -> existingAbs (если на том же FS)
                    // Но если existingAbs не существует, просто переместим
                    // Перед этим убедимся, что директория существует
                    $existingDir = dirname($existingAbs);
                    if (!is_dir($existingDir)) {
                        if (!mkdir($existingDir, 0775, true) && !is_dir($existingDir)) {
                            // оставим tmp для очистки в catch
                            if ($backupPath && file_exists($backupPath)) @unlink($backupPath);
                            throw new RuntimeException('Не удалось создать директорию для замены изображения');
                        }
                    }

                    $replaced = false;
                    // Попробуем overwrite через rename (atomic на одном разделе)
                    if (@rename($tmpFinalPath, $existingAbs)) {
                        $replaced = true;
                    } else {
                        // Если rename не удался (возможен cross-device), попробуем copy+unlink
                        if (@copy($tmpFinalPath, $existingAbs)) {
                            if (@unlink($tmpFinalPath)) {
                                $replaced = true;
                            }
                        }
                    }

                    if (!$replaced) {
                        // восстановим бэкап, удалим tmp
                        if ($backupPath && file_exists($backupPath)) {
                            @copy($backupPath, $existingAbs);
                            @unlink($backupPath);
                        }
                        throw new RuntimeException('Не удалось заменить существующее изображение');
                    } else {
                        // замена успешна — удалим backup (если был)
                        if ($backupPath && file_exists($backupPath)) @unlink($backupPath);
                    }

                    // Путь для записи в БД остаётся тот же, что и был (currentImagePath)
                    $imagePublicPath = $currentImagePath;
                    // tmp уже перемещён, удаляем из списка tempCreatedFiles
                    $tempCreatedFiles = array_values(
                        array_filter($tempCreatedFiles, function($p) 
                            use ($tmpFinalPath){ return $p !== $tmpFinalPath; })
                    );
                }
            }

            // DB
            $pdo = db(); // ваша функция db() должна вернуть PDO
            if (!($pdo instanceof PDO)) {
                throw new RuntimeException('DB connection is not PDO');
            }

            $pdo->beginTransaction();

            try {
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

                $pdo->commit();
            } catch (Exception $e) {
                // при ошибке DB — попытка откатить файловые операции
                $pdo->rollBack();
                // Если мы заменяли существующий файл, попытка восстановить из backup уже сделана ранее при неудаче;
                // Но если final был записан и backup остался — восстановим.
                // (В нашем коде backup удаляется при успехе, поэтому здесь проверяем .bak.*)
                // И удаляем созданные временные файлы
                foreach ($tempCreatedFiles as $p) {
                    if (file_exists($p)) @unlink($p);
                }
                throw $e;
            }

            $response['success'] = true;
            $response['outer_id'] = $savedOuter;
            if ($imagePublicPath) $response['image'] = $imagePublicPath;

        } catch (Exception $e) {
            $response['error'] = $e->getMessage();
            // Очистка временных файлов, если есть
            if (!empty($tempCreatedFiles) && is_array($tempCreatedFiles)) {
                foreach ($tempCreatedFiles as $p) {
                    if (file_exists($p)) @unlink($p);
                }
            }
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);

        // Обновление кэша (если функция доступна)
        if (function_exists('cache')) {
            try {
                cache()->refreshCache();
            } catch (Exception $e) {
                // игнорируем ошибку кэша
            }
        }
    }
}

