<?php
namespace App\Controller;

// back_url уже записался автоматически в родителе!
class CategoryController extends BaseController
{
    
    public static function index()
    {
        self::init(); // запись URL

        if ($slug = request()->get_SLUG_or_ID()[0]) {

            $cache = cache()->getCache_db(); 
            $products = $cache['by_category'][$slug] ?? [];

            if (empty($products)) {
                $products = db()->query("SELECT * FROM " . TABLE_NAME . " WHERE slug = ?", [$slug])->get();
            
            }
            
            shuffle($products);
            
            if (TABLE_NAME == 'cosmetics') {
                $title = self::getTitleByInternalKey($products[0]['slug']) .' на Japan-in.Ru';

            } else {
                $title = 'Японский уход, косметика и витамины — секреты твоей красоты!';
            
            }

            return app()->view->full_view(

                CATEGORY_LAYOUT,
                CATEGORY_VIEW, 
                [
                    'title'    => $title,
                    'products' => $products,
                    //[0] - индекс первого продукта
                    'category' => $products[0]['category'] ?? '',
                    'title_category' => self::getTitleByInternalKey($products[0]['slug']),
                    'slug'     => $slug
                ]
            );
        
        }

        return app()->view->full_view (HOME_LAYOUT, HOME_VIEW, []);
        
    }


    /**
    * Функция генерации SEO-заголовка по внутреннему ключу категории
    *
    * @param string $key Ключ из исходного массива (например, 'makeup', 'gift-set')
    * @return string Готовый заголовок Title
    */
    private static function getTitleByInternalKey($key) {
        // Исходный массив соответствия ключей и названий
        $categories = [
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

        // Базовая проверка: если ключа нет в списке, возвращаем общее название
        if (!isset($categories[$key])) { return "Косметика"; }

        $categoryName = $categories[$key];

        // --- ЛОГИКА ОБРАБОТКИ СПЕЦСЛУЧАЕВ ---

        // Декоративная косметика (Всегда с большой буквы, без добавления слова "Косметика")
        if ($key === 'makeup') { return "Декоративная косметика"; }

        // Полость рта (Полная замена фразы по вашему требованию)
        if ($key === 'for-oral-cavity') { return "Средства по уходу за полостью рта"; }

        // Подарочные наборы (Смена предлога и падежа)
        if ($key === 'gift-set') { return "Косметика в подарочных наборах"; }

        // --- ЛОГИКА ДЛЯ ОСТАЛЬНЫХ КАТЕГОРИЙ ---

        // Определение связки (союз или предлог)
        if ($key === 'aromatherapy' || $key === 'accessories') {
            $link = "и";

        } elseif (strpos($categoryName, 'для') === 0) {
            $link = ""; // Если "для" уже есть в названии категории

        } else {
            $link = "для";
        }

        // Сборка финальной строки (Косметика + связка + название)
        $title = "Косметика" . ($link ? " {$link} " : " ") . $categoryName;

        return $title;
    
    }

    // ПРИМЕРЫ ВЫЗОВА:
    // echo getTitleByInternalKey('makeup');          // Выведет: Декоративная косметика
    // echo getTitleByInternalKey('for-oral-cavity'); // Выведет: Средства по уходу за полостью рта
    // echo getTitleByInternalKey('for-face');        // Выведет: Косметика для лица
    // echo getTitleByInternalKey('gift-set');        // Выведет: Косметика в подарочных наборах
    // echo getTitleByInternalKey('accessories');     // Выведет: Косметика и аксессуары

}





