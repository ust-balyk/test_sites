<?php
declare(strict_types=1);

namespace App\Helper\Image;

use GdImage;
use RuntimeException;

class Image
{
    private const TARGET_RATIO = 3 / 4;
    private const OBJECT_FILL = 0.96;
    private const BG_COLOR_HEX = '#fffffd';
    private const THRESHOLD = 35; // порог прозрачности (расстояние цвета)
    private const FINAL_HEIGHT = 1200;
    private const WORK_SCALE = 1200; // рабочая высота для предварительной обработки
    private const SAMPLE = 5;

    public function changeImg(GdImage $image, string $locPath): bool
    {
        if (!extension_loaded('gd')) {
            throw new RuntimeException('GD extension not available');
        }

        // 1. подготовка - рабочий масштаб
        $working = imagescale($image, self::WORK_SCALE);
        if ($working === false) {
            throw new RuntimeException('Failed to scale image');
        }

        $width = imagesx($working);
        $height = imagesy($working);

        // 2. hex -> rgb
        [$newR, $newG, $newB] = $this->hexToRgb(self::BG_COLOR_HEX);

        // 3. автоопределение фона (усреднение 4 углов)
        $target = $this->detectBackgroundColor($working, $width, $height, self::SAMPLE);

        // 4. flood-fill удаление фона (оптимизированная visited как одномерный ключ)
        $thresholdSq = self::THRESHOLD * self::THRESHOLD;
        $this->floodRemoveBackground($working, $width, $height, $target, $thresholdSq);

        // 5. поиск границ (trim)
        $trim = $this->trimBounds($working, $width, $height);
        if ($trim === null) {
            imagedestroy($working);
            return false; // не найден объект
        }
        [$left, $top, $right, $bottom] = $trim;

        $objW = $right - $left + 1;
        $objH = $bottom - $top + 1;

        // 6. расчет холста
        $canvasW = (int)($objW / self::OBJECT_FILL);
        $canvasH = (int)($objH / self::OBJECT_FILL);

        if ($canvasW / $canvasH > self::TARGET_RATIO) {
            $canvasH = (int)($canvasW / self::TARGET_RATIO);
        } else {
            $canvasW = (int)($canvasH * self::TARGET_RATIO);
        }

        // 7. сборка итогового холста
        $finalCanvas = imagecreatetruecolor($canvasW, $canvasH);
        if ($finalCanvas === false) {
            imagedestroy($working);
            throw new RuntimeException('Failed to create canvas');
        }

        // заполнение фоном
        $fillColor = imagecolorallocate($finalCanvas, $newR, $newG, $newB);
        imagefill($finalCanvas, 0, 0, $fillColor);

        // включаем альфу и копируем объект
        imagesavealpha($finalCanvas, true);
        imagealphablending($finalCanvas, false);

        $destX = (int)round(($canvasW - $objW) / 2);
        $destY = (int)round(($canvasH - $objH) / 2);

        // Копируем с сохранением альфы
        imagecopy($finalCanvas, $working, $destX, $destY, $left, $top, $objW, $objH);

        // Финальное масштабирование
        $resultW = (int)round(self::FINAL_HEIGHT * self::TARGET_RATIO);
        $result = imagescale($finalCanvas, $resultW, self::FINAL_HEIGHT, IMG_BICUBIC);
        if ($result === false) {
            imagedestroy($working);
            imagedestroy($finalCanvas);
            throw new RuntimeException('Final scaling failed');
        }

        // Резкость
        $sharpenMatrix = [
            [-1.0, -1.0, -1.0],
            [-1.0, 16.0, -1.0],
            [-1.0, -1.0, -1.0],
        ];
        $divisor = array_sum(array_map('array_sum', $sharpenMatrix)) ?: 1;
        @imageconvolution($result, $sharpenMatrix, $divisor, 0);

        // Сохранение как WebP (проверяем поддержку)
        if (!function_exists('imagewebp')) {
            imagedestroy($working);
            imagedestroy($finalCanvas);
            imagedestroy($result);
            throw new RuntimeException('WebP not supported by GD build');
        }
        $saved = imagewebp($result, $locPath, 90);

        // очистка
        imagedestroy($working);
        imagedestroy($finalCanvas);
        imagedestroy($result);

        return (bool)$saved;
    }

    private function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return [$r, $g, $b];
    }

    private function detectBackgroundColor(GdImage $img, int $w, int $h, int $sample): array
    {
        $corners = [
            [0, 0],
            [$w - $sample, 0],
            [0, $h - $sample],
            [$w - $sample, $h - $sample],
        ];
        $tR = $tG = $tB = 0;
        $count = 0;
        foreach ($corners as $c) {
            for ($x = $c[0]; $x < $c[0] + $sample; $x++) {
                for ($y = $c[1]; $y < $c[1] + $sample; $y++) {
                    $col = imagecolorat($img, $x, $y);
                    $rgb = imagecolorsforindex($img, $col);
                    $tR += $rgb['red']; $tG += $rgb['green']; $tB += $rgb['blue'];
                    $count++;
                }
            }
        }
        return ['r' => $tR / max(1, $count), 'g' => $tG / max(1, $count), 'b' => $tB / max(1, $count)];
    }

    private function floodRemoveBackground(GdImage $img, int $w, int $h, array $target, int $thresholdSq): void
    {
        imagealphablending($img, false);
        imagesavealpha($img, true);

        $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);

        // visited[idx] == true => уже обработали координату (x,y)
        $visited = array_fill(0, $w * $h, false);

        $stackX = [0, $w - 1, 0, $w - 1];
        $stackY = [0, 0, $h - 1, $h - 1];
        $sp = 4; // stack pointer

        while ($sp > 0) {
            $sp--;
            $x = $stackX[$sp];
            $y = $stackY[$sp];

            // защита на случай если вы когда-то добавите координаты вне диапазона
            if ($x < 0 || $y < 0 || $x >= $w || $y >= $h) continue;

            $idx = $y * $w + $x;
            if ($visited[$idx]) continue;
            $visited[$idx] = true;

            $col = imagecolorat($img, $x, $y);
            $c = imagecolorsforindex($img, $col);

            // GD может вернуть alpha как int; если нет — считаем 0
            $alpha = $c['alpha'] ?? 0;
            if ($alpha >= 127) continue;

            $dr = $c['red'] - $target['r'];
            $dg = $c['green'] - $target['g'];
            $db = $c['blue'] - $target['b'];

            $distSq = $dr * $dr + $dg * $dg + $db * $db;

            if ($distSq <= $thresholdSq) {
                imagesetpixel($img, $x, $y, $transparent);

                $nx = $x + 1;
                if ($nx >= 0 && $nx < $w) { $stackX[$sp] = $nx; $stackY[$sp] = $y; $sp++; }
                $nx = $x - 1;
                if ($nx >= 0 && $nx < $w) { $stackX[$sp] = $nx; $stackY[$sp] = $y; $sp++; }
                $ny = $y + 1;
                if ($ny >= 0 && $ny < $h) { $stackX[$sp] = $x; $stackY[$sp] = $ny; $sp++; }
                $ny = $y - 1;
                if ($ny >= 0 && $ny < $h) { $stackX[$sp] = $x; $stackY[$sp] = $ny; $sp++; }
            }
        }
    }


    // Возвращает [left, top, right, bottom] или null если не найден объект    
    private function trimBounds(GdImage $img, int $w, int $h): ?array
    {
        $top = 0; $bottom = $h - 1; $left = 0; $right = $w - 1;
        $found = false;

        // сверху вниз
        for (; $top < $h; $top++) {
            for ($x = 0; $x < $w; $x++) {
                if (((imagecolorat($img, $x, $top) >> 24) & 0x7F) < 110) { $found = true; break 2; }
            }
        }
        if (!$found) return null;

        // снизу вверх
        for (; $bottom > $top; $bottom--) {
            for ($x = 0; $x < $w; $x++) {
                if (((imagecolorat($img, $x, $bottom) >> 24) & 0x7F) < 110) { break 2; }
            }
        }

        // слева направо
        for (; $left < $w; $left++) {
            for ($y = $top; $y <= $bottom; $y++) {
                if (((imagecolorat($img, $left, $y) >> 24) & 0x7F) < 110) { break 2; }
            }
        }

        // справа налево
        for (; $right > $left; $right--) {
            for ($y = $top; $y <= $bottom; $y++) {
                if (((imagecolorat($img, $right, $y) >> 24) & 0x7F) < 110) { break 2; }
            }
        }

        return [$left, $top, $right, $bottom];
    }
}

