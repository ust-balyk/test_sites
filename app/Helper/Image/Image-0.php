<?php
namespace App\Helper\Image;

define('IMG_TARGET_RATIO', 3/4);       // Пропорция сторон (3:4)
define('IMG_OBJECT_FILL', 0.96);       // Объект занимает N% кадра
define('IMG_BG_COLOR_HEX', '#fffffd'); // Новый цвет фона
define('IMG_THRESHOLD', 35);           // Допуск прозрачности (чем выше, тем сильнее удаление)
define('IMG_FINAL_HEIGHT', 1200);      // Итоговое разрешение по высоте

class Image
{
    function change_img($image, $loc_path_image) 
    {
        // 1. подготовка
        $image = imagescale($image, 1200); // Рабочий масштаб
        $width = imagesx($image);
        $height = imagesy($image);

        // 2. HEX -> RGB из константы
        $hex = str_replace("#", "", IMG_BG_COLOR_HEX);
        $newR = hexdec(substr($hex, 0, 2));
        $newG = hexdec(substr($hex, 2, 2));
        $newB = hexdec(substr($hex, 4, 2));

        // 3. Автоопределение текущего фона (4 угла)
        $sample = 5;
        $corners = [[0,0], [$width-$sample, 0], [0, $height-$sample], [$width-$sample, $height-$sample]];
        $tR = $tG = $tB = 0;
        foreach ($corners as $c) {
            for ($x=$c[0]; $x<$c[0]+$sample; $x++) {
                for ($y=$c[1]; $y<$c[1]+$sample; $y++) {
                    $rgb = imagecolorsforindex($image, imagecolorat($image, $x, $y));
                    $tR += $rgb['red']; $tG += $rgb['green']; $tB += $rgb['blue'];
                }
            }
        }
        $target = ['r' => $tR/100, 'g' => $tG/100, 'b' => $tB/100];

        // 4. Удаление фона (Flood Fill) — ЗАЩИТА ЗУБОВ
        imagealphablending($image, false);
        imagesavealpha($image, true);
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        $thresholdSq = IMG_THRESHOLD * IMG_THRESHOLD;

        $stack = [[0,0], [$width-1, 0], [0, $height-1], [$width-1, $height-1]];
        $visited = [];
        while (!empty($stack)) {
            list($x, $y) = array_pop($stack);
            if ($x < 0 || $y < 0 || $x >= $width || $y >= $height || isset($visited[$x][$y])) continue;
            $visited[$x][$y] = true;
            $c = imagecolorsforindex($image, imagecolorat($image, $x, $y));
            $distSq = pow($c['red']-$target['r'],2) + pow($c['green']-$target['g'],2) + pow($c['blue']-$target['b'],2);
            if ($distSq <= $thresholdSq && $c['alpha'] < 127) {
                imagesetpixel($image, $x, $y, $transparent);
                $stack[]=[$x+1,$y]; $stack[]=[$x-1,$y]; $stack[]=[$x,$y+1]; $stack[]=[$x,$y-1];
            }
        }
        /*
        // 5. Поиск границ (Trim)
        $top = $height; $bottom = 0; $left = $width; $right = 0; $found = false;
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if (((imagecolorat($image, $x, $y) >> 24) & 0x7F) < 110) {
                    if ($x < $left) $left = $x; if ($x > $right) $right = $x;
                    if ($y < $top) $top = $y; if ($y > $bottom) $bottom = $y;
                    $found = true;
                }
            }
        }
        */
        // 5. Оптимизированный поиск границ (Trim)
        $top = 0; $bottom = $height - 1; $left = 0; $right = $width - 1; $found = false;

        // Сверху вниз
        for (; $top < $height; $top++) {
            for ($x = 0; $x < $width; $x++) {
                if (((imagecolorat($image, $x, $top) >> 24) & 0x7F) < 110) { $found = true; break 2; }
            }
        }
        if (!$found) return false; // Если ничего не нашли, дальше можно не искать

        // Снизу вверх
        for (; $bottom > $top; $bottom--) {
            for ($x = 0; $x < $width; $x++) {
                if (((imagecolorat($image, $x, $bottom) >> 24) & 0x7F) < 110) { break 2; }
            }
        }

        // Слева направо
        for (; $left < $width; $left++) {
            for ($y = $top; $y <= $bottom; $y++) {
                if (((imagecolorat($image, $left, $y) >> 24) & 0x7F) < 110) { break 2; }
            }
        }

        // Справа налево
        for (; $right > $left; $right--) {
            for ($y = $top; $y <= $bottom; $y++) {
                if (((imagecolorat($image, $right, $y) >> 24) & 0x7F) < 110) { break 2; }
            }
        }
        

        if (!$found) return false;

        // 6. Расчет холста по константам RATIO и FILL
        $objW = $right - $left + 1; $objH = $bottom - $top + 1;
        $canvasW = (int)($objW / IMG_OBJECT_FILL);
        $canvasH = (int)($objH / IMG_OBJECT_FILL);

        if ($canvasW / $canvasH > IMG_TARGET_RATIO) {
            $canvasH = (int)($canvasW / IMG_TARGET_RATIO);
        } else {
            $canvasW = (int)($canvasH * IMG_TARGET_RATIO);
        }

        // 7. Сборка
        
        $finalCanvas = imagecreatetruecolor($canvasW, $canvasH);
        $fillColor = imagecolorallocate($finalCanvas, $newR, $newG, $newB);
        imagefill($finalCanvas, 0, 0, $fillColor);

        imagealphablending($finalCanvas, true);
        imagecopy($finalCanvas, $image, round(($canvasW-$objW)/2), round(($canvasH-$objH)/2), $left, $top, $objW, $objH);

        // Финальное масштабирование
        
        $resultW = (int)(IMG_FINAL_HEIGHT * IMG_TARGET_RATIO);
        
        // Используем максимально качественный алгоритм BICUBIC
        $result = imagescale($finalCanvas, $resultW, IMG_FINAL_HEIGHT, IMG_BICUBIC);
        
        // МАТРИЦА РЕЗКОСТИ
        // Эта матрица усиливает контраст центрального пикселя относительно соседних
        $sharpenMatrix = [
            [-1.0, -1.0, -1.0],
            [-1.0, 16.0, -1.0],
            [-1.0, -1.0, -1.0]
        ];
        
        // Вычисляем делитель (сумма всех чисел матрицы), чтобы яркость не изменилась
        $divisor = array_sum(array_map('array_sum', $sharpenMatrix));
        
        // Применяем фильтр резкости
        imageconvolution($result, $sharpenMatrix, $divisor, 0);

        // СОХРАНЕНИЕ
        // Для WebP или JPG качество 85-90 дает лучший баланс веса и четкости
        //$new_img = imagejpeg($result, $loc_path_image, 90);
        //$new_img = imagewebp($result, $loc_path_image .".webp", 90);
        $new_img = imagewebp($result, $loc_path_image, 90);

        // Очистка памяти
        imagedestroy($image);
        imagedestroy($finalCanvas);
        imagedestroy($result);

        return $new_img;
        
    }


}
