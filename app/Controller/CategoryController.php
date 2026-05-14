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
                // отображение категории корректируется по slug
                $title = self::getTitle_External($products[0]['slug']) .' на Japan-in.Ru';

            } else {
                $title = 'Японский уход, косметика и витамины — секреты твоей красоты!';
            
            }
            return app()->view->full_view(

                CATEGORY_LAYOUT,
                CATEGORY_VIEW, 
                [
                    'title'    => mb_ucfirst($title),
                    'products' => $products,
                    //[0] - индекс первого продукта
                    'category' => $products[0]['category'] ?? '',
                    // отображение категории корректируется по slug
                    'title_category' => self::getTitle_Internal($products[0]['slug']),
                    'slug'     => $slug
                ]
            );
        
        }
        return app()->view->full_view (HOME_LAYOUT, HOME_VIEW, []);
        
    }


    private static function getTitle_External($key) {

        $categories = self::$categories;

        if (!isset($categories[$key])) { return "косметика"; }
        
        $categoryName = $categories[$key];

        if ($key === 'makeup') { return "декоративная косметика"; }
        if ($key === 'for-oral-cavity') { return "средства по уходу за полостью рта"; }
        if ($key === 'gift-set') { return "косметика в подарочных наборах"; }
        
        if ($key === 'aromatherapy' || $key === 'accessories') {
            $link = "и";
        
        } elseif (strpos($categoryName, 'для') === 0) {
            $link = ""; // Если "для" уже есть в названии категории
        
        } else {
            $link = "для";
        
        }
        $title = "косметика" . ($link ? " {$link} " : " ") . $categoryName;

        return $title;

    }
    

    private static function getTitle_Internal($key) {

        $categories = self::$categories;

        if (!isset($categories[$key])) { return "косметика"; }

        $categoryName = $categories[$key];

        if ($key === 'makeup') { return "декоративная косметика"; }
        if ($key === 'for-body') { return "безупречность тела"; }
        if ($key === 'for-face') { return "сияние твоего лица"; }
        if ($key === 'for-oral-cavity') { return "волшебство улыбки"; }
        if ($key === 'for-hair') { return "блеск твоих волос"; }
        if ($key === 'for-hands') { return "очарование на кончиках пальцев"; }
        if ($key === 'for-feet') { return "красота твоих ножек"; }
        if ($key === 'gift-set') { return "косметика в подарочных наборах"; }

        // определение связки (союз или предлог)
        if ($key === 'aromatherapy' || $key === 'accessories') {
            $link = "и";

        } elseif (strpos($categoryName, 'для') === 0) {
            $link = ""; // Если "для" уже есть в названии категории

        } else {
            $link = "для";
        
        }
        // косметика + связка + название
        $title_category = "косметика" . ($link ? " {$link} " : " ") . $categoryName;

        return $title_category;
    
    }


}

