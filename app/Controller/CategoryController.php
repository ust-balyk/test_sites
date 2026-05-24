<?php
namespace App\Controller;

class CategoryController extends BaseController
{
    private static $categories = [
        "makeup"          => "декоративная косметика",
        "for-body"        => "для тела",
        "for-face"        => "для лица",
        "for-oral-cavity" => "для полости рта",
        "for-hair"        => "для волос",
        "for-hands"       => "для рук",
        "for-feet"        => "для ног",
        "aromatherapy"    => "ароматерапия",
        "gift-set"        => "подарочные наборы",
        "accessories"     => "аксессуары",
    ];

    // Специальные заголовки
    private static $titleExternal = [
        'makeup'           => "декоративная косметика",
        'for-oral-cavity'  => "средства по уходу за полостью рта",
        'gift-set'         => "косметика в подарочных наборах",
    ];

    private static $titleInternal = [
        'makeup'           => "декоративная косметика",
        'for-body'         => "безупречность тела",
        'for-face'         => "сияние твоего лица",
        'for-oral-cavity'  => "волшебство улыбки",
        'for-hair'         => "блеск твоих волос",
        'for-hands'        => "очарование на кончиках пальцев",
        'for-feet'         => "красота твоих ножек",
        'gift-set'         => "косметика в подарочных наборах",
    ];

    public static function index()
    {
        self::init();
    
        $requestedSlug = request()->get_SLUG_or_ID()[0] ?? null;
        
        // Если есть запрос, сначала пытаемся получить по нему
        if ($requestedSlug) {
            $products = self::getProducts($requestedSlug);
            $usedSlug = $requestedSlug;
        } else {
            // ❌ Если $requestedSlug = null, переменные $products и $usedSlug не определены!
            $products = [];
            $usedSlug = null;
        }
    
        // Если товары по запросу не найдены, ищем первую категорию с товарами
        if (empty($products)) {
            foreach (self::$categories as $categorySlug => $categoryName) {
                $products = self::getProducts($categorySlug);
                
                if (!empty($products)) {
                    $usedSlug = $categorySlug;
                    break;
                }
            }
        }
    
        // Если совсем ничего не нашли, возвращаем главную
        if (empty($products)) {
            return app()->view->full_view(HOME_LAYOUT, HOME_VIEW, []);
        }
    
        $firstProduct = $products[0];
    
        // Перемешиваем товары
        $seed = crc32(date('Y-m-d') . ':' . $firstProduct['slug']);
        $products = self::seeded_shuffle($products, $seed);
    
        // Определяем заголовок
        $title = (TABLE_NAME == 'cosmetics') 
            ? self::getTitle_External($firstProduct['slug']) . ' на Japan-in.Ru'
            : 'Японский уход, косметика и витамины — секреты твоей красоты!';
    
        return app()->view->full_view(
            CATEGORY_LAYOUT,
            CATEGORY_VIEW, 
            [
                'title'          => mb_ucfirst($title),
                'products'       => $products,
                'category'       => $firstProduct['category'] ?? '',
                'title_category' => self::getTitle_Internal($firstProduct['slug']),
                'slug'           => $usedSlug
            ]
        );
    }
    
    /**
     * Получить товары с использованием кеша
     */
    private static function getProducts($slug)
    {
        $cache = cache()->getCache_db();
        $products = $cache['by_category'][$slug] ?? [];
        
        if (empty($products)) {
            $products = db()->query(
                "SELECT * FROM " . TABLE_NAME . " WHERE slug = ?", 
                [$slug]
            )->get();
            
            if (!empty($products)) {
                cache()->refreshCache();
            }
        }
        
        return $products;
    }

    /**
     * Определить заголовок для SEO (внешний)
     */
    private static function getTitle_External($key)
    {
        // Проверяем специальные заголовки
        if (isset(self::$titleExternal[$key])) {
            return self::$titleExternal[$key];
        }

        // Если ключ не найден в категориях
        if (!isset(self::$categories[$key])) {
            return "косметика";
        }

        $categoryName = self::$categories[$key];
        $link = self::getLinkWord($key, $categoryName);

        return "косметика" . ($link ? " {$link} " : " ") . $categoryName;
    }

    /**
     * Определить заголовок для внутреннего отображения
     */
    private static function getTitle_Internal($key)
    {
        // Проверяем специальные заголовки
        if (isset(self::$titleInternal[$key])) {
            return self::$titleInternal[$key];
        }

        // Если ключ не найден в категориях
        if (!isset(self::$categories[$key])) {
            return "косметика";
        }

        $categoryName = self::$categories[$key];
        $link = self::getLinkWord($key, $categoryName);

        return "косметика" . ($link ? " {$link} " : " ") . $categoryName;
    }

    /**
     * Определить союз/предлог для связи слов
     */
    private static function getLinkWord($key, $categoryName)
    {
        if ($key === 'aromatherapy' || $key === 'accessories') {
            return "и";
        }

        if (strpos($categoryName, 'для') === 0) {
            return ""; // "для" уже есть в названии
        }

        return "для";
    }

    /**
     * Перемешивает товары категории, используя детерминированный seed
     * Не влияет на глобальный генератор RNG
     */
    private static function seeded_shuffle(array $arr, int $seed): array
    {
        $n = count($arr);
        $state = $seed;
        
        $rand = function($min, $max) use (&$state) {
            $state = (1103515245 * $state + 12345) & 0x7FFFFFFF;
            return $min + ($state % ($max - $min + 1));
        };
        
        for ($i = $n - 1; $i > 0; $i--) {
            $j = $rand(0, $i);
            [$arr[$i], $arr[$j]] = [$arr[$j], $arr[$i]];
        }
        
        return $arr;
    }
}

