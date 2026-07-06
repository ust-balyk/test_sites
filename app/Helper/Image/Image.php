<?php
namespace App\Helper\Image;

class Image
{
    private const IMG_TARGET_RATIO = 3/4;       // Пропорция сторон (3:4)
    private const IMG_OBJECT_FILL  = 0.96;      // Объект занимает N% кадра
    private const IMG_BG_COLOR_HEX = '#fffffd'; // Новый цвет фона
    private const IMG_THRESHOLD    = 35;        // Допуск прозрачности (чем выше, тем сильнее удаление)
    private const IMG_FINAL_HEIGHT = 1200;      // Итоговое разрешение по высоте

    public function changeImg($image, string $loc_path_image)
    {
        if (!$image) return false;

        // 1) подготовка
        $image = imagescale($image, 1200); // Рабочий масштаб
        $width = imagesx($image);
        $height = imagesy($image);

        if ($width < 2 || $height < 2) return false;

        // 1.1) гарантируем alpha-режим на рабочем холсте
        imagealphablending($image, false);
        imagesavealpha($image, true);

        // 2) HEX -> RGB из константы
        $hex = str_replace("#", "", self::IMG_BG_COLOR_HEX);
        $newR = hexdec(substr($hex, 0, 2));
        $newG = hexdec(substr($hex, 2, 2));
        $newB = hexdec(substr($hex, 4, 2));

        // 3) Автоопределение текущего фона (4 угла) — по RGB
        $sample = 5;
        $corners = [
            [0, 0],
            [$width - $sample, 0],
            [0, $height - $sample],
            [$width - $sample, $height - $sample],
        ];

        $tR = 0; $tG = 0; $tB = 0;
        $count = 0;

        foreach ($corners as $c) {
            $x0 = max(0, (int)$c[0]);
            $y0 = max(0, (int)$c[1]);
            $x1 = min($width - 1, $x0 + $sample - 1);
            $y1 = min($height - 1, $y0 + $sample - 1);

            for ($x = $x0; $x <= $x1; $x++) {
                for ($y = $y0; $y <= $y1; $y++) {
                    $idx = imagecolorat($image, $x, $y);
                    $rgb = imagecolorsforindex($image, $idx);
                    $tR += $rgb['red'];
                    $tG += $rgb['green'];
                    $tB += $rgb['blue'];
                    $count++;
                }
            }
        }

        if ($count === 0) return false;

        $target = ['r' => $tR / $count, 'g' => $tG / $count, 'b' => $tB / $count];

        // 4) Удаление фона (Flood Fill) — оптимизировано по памяти
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        $thresholdSq = self::IMG_THRESHOLD * self::IMG_THRESHOLD;

        // ВАЖНО: фиксируем худший случай по числу посещённых пикселей,
        // чтобы не упираться в память/время на огромных областях.
        $maxVisited = 3_000_000;
        $visitedCount = 0;

        // Плотный visited-буфер: array строк, каждая строка = $width байт (0/1)
        $visited = array_fill(0, $height, str_repeat("\0", $width));

        $stack = [
            [0, 0],
            [$width - 1, 0],
            [0, $height - 1],
            [$width - 1, $height - 1],
        ];

        while (!empty($stack)) {
            [$x, $y] = array_pop($stack);

            if ($x < 0 || $y < 0 || $x >= $width || $y >= $height) continue;

            $byte = ord($visited[$y][$x]);
            if ($byte === 1) continue;

            $visited[$y][$x] = "\1";
            $visitedCount++;
            if ($visitedCount > $maxVisited) break;

            $idx = imagecolorat($image, $x, $y);
            $c = imagecolorsforindex($image, $idx);

            $distSq =
                ($c['red'] - $target['r']) ** 2 +
                ($c['green'] - $target['g']) ** 2 +
                ($c['blue'] - $target['b']) ** 2;

            // 0..127: 0 непрозрачный, 127 полностью прозр.
            if ($distSq <= $thresholdSq && $c['alpha'] < 127) {
                imagesetpixel($image, $x, $y, $transparent);
                $stack[] = [$x + 1, $y];
                $stack[] = [$x - 1, $y];
                $stack[] = [$x, $y + 1];
                $stack[] = [$x, $y - 1];
            }
        }

        // 5) Поиск границ (Trim) — по альфе через imagecolorsforindex
        $alphaCut = 110;

        $top = 0; $bottom = $height - 1; $left = 0; $right = $width - 1; $found = false;

        // Сверху вниз
        for (; $top < $height; $top++) {
            for ($x = 0; $x < $width; $x++) {
                $c = imagecolorsforindex($image, imagecolorat($image, $x, $top));
                if ($c['alpha'] < $alphaCut) { $found = true; break 2; }
            }
        }
        if (!$found) return false;

        // Снизу вверх
        for (; $bottom > $top; $bottom--) {
            $rowHas = false;
            for ($x = 0; $x < $width; $x++) {
                $c = imagecolorsforindex($image, imagecolorat($image, $x, $bottom));
                if ($c['alpha'] < $alphaCut) { $rowHas = true; break; }
            }
            if ($rowHas) break;
        }

        // Слева направо
        for (; $left < $width; $left++) {
            $colHas = false;
            for ($y = $top; $y <= $bottom; $y++) {
                $c = imagecolorsforindex($image, imagecolorat($image, $left, $y));
                if ($c['alpha'] < $alphaCut) { $colHas = true; break; }
            }
            if ($colHas) break;
        }

        // Справа налево
        for (; $right > $left; $right--) {
            $colHas = false;
            for ($y = $top; $y <= $bottom; $y++) {
                $c = imagecolorsforindex($image, imagecolorat($image, $right, $y));
                if ($c['alpha'] < $alphaCut) { $colHas = true; break; }
            }
            if ($colHas) break;
        }

        if ($right <= $left || $bottom <= $top) return false;

        // 6) Расчет холста по константам RATIO и FILL
        $objW = $right - $left + 1;
        $objH = $bottom - $top + 1;

        $canvasW = (int)($objW / self::IMG_OBJECT_FILL);
        $canvasH = (int)($objH / self::IMG_OBJECT_FILL);

        if ($canvasW < 1 || $canvasH < 1) return false;

        if ($canvasW / $canvasH > self::IMG_TARGET_RATIO) {
            $canvasH = (int)($canvasW / self::IMG_TARGET_RATIO);
        } else {
            $canvasW = (int)($canvasH * self::IMG_TARGET_RATIO);
        }

        if ($canvasW < 1 || $canvasH < 1) return false;

        // 7) Сборка
        $finalCanvas = imagecreatetruecolor($canvasW, $canvasH);

        // важное: сохраняем alpha на finalCanvas
        imagealphablending($finalCanvas, false);
        imagesavealpha($finalCanvas, true);

        // фон (непрозрачный)
        $fillColor = imagecolorallocate($finalCanvas, $newR, $newG, $newB);
        imagefilledrectangle($finalCanvas, 0, 0, $canvasW - 1, $canvasH - 1, $fillColor);

        imagealphablending($finalCanvas, true);

        $dstX = (int)round(($canvasW - $objW) / 2);
        $dstY = (int)round(($canvasH - $objH) / 2);

        imagecopy($finalCanvas, $image, $dstX, $dstY, $left, $top, $objW, $objH);

        // Финальное масштабирование
        $resultW = (int)(self::IMG_FINAL_HEIGHT * self::IMG_TARGET_RATIO);

        $result = imagescale($finalCanvas, $resultW, self::IMG_FINAL_HEIGHT, IMG_BICUBIC);
        if (!$result) return false;

        // МАТРИЦА РЕЗКОСТИ
        $sharpenMatrix = [
            [-1.0, -1.0, -1.0],
            [-1.0, 16.0, -1.0],
            [-1.0, -1.0, -1.0],
        ];

        $divisor = array_sum(array_map('array_sum', $sharpenMatrix));
        if ($divisor == 0) $divisor = 1;

        imageconvolution($result, $sharpenMatrix, $divisor, 0);

        // СОХРАНЕНИЕ (WebP)
        // Для WebP или JPG качество 85-90 дает лучший баланс веса и четкости
        // $new_img = imagejpeg($result, $loc_path_image . "jpg", 90);
        $new_img = imagewebp($result, $loc_path_image . ".webp", 90);

        // Очистка памяти
        imagedestroy($image);
        imagedestroy($finalCanvas);
        imagedestroy($result);

        return $new_img;
    }
}

