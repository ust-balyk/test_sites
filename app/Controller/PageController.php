<?php
namespace App\Controller;
use Master\Pagination;
use App\Cart\Cart;


class PageController extends BaseController
{

    static function cosmetics()
    {
        self::init(); // путь на страницу из login/registr
    
        $items_by_cache = cache()->getCache_db();
        $cosmetics = $items_by_cache['by_id'] ?? [];
    
        // если нет в кэше — возьмём из БД
        if (empty($cosmetics)) {
            $items_by_db = db()->query("SELECT * FROM ". TABLE_NAME)->getAssoc('id');
            $cosmetics = $items_by_db ?? [];
        }

        if (! empty($cosmetics)) {
            // если нет сохранённого порядка — перемешиваем и сохраняем
            if (empty($_SESSION['shuffled_keys'])) {
                $cosmetics = shuffle_assoc($cosmetics); // внутри: $_SESSION['shuffled_keys'] = $keys;
            } else {
                $cosmetics = restore_order($cosmetics);
            }
    
            return app()->view->full_view(
                CATEGORY_LAYOUT,
                'cosmetics',
                [
                    'cosmetics' => $cosmetics, 
                    'title'     =>'', 
                    'short_description'=>'', 
                    'full_url'  =>'', 
                    'image_url' =>''
                ]
            );
        }
        return app()->view->full_view(HOME_LAYOUT, HOME_VIEW, []);

    }



    static function discount()
    {
        self::init(); // запись URL

        $all_products = cache()->getCache_db();
        $discounted_products = array_filter($all_products, function($product) {
            return isset($product['price']) && $product['price'] === '';

        });
        // ORDER BY id DESC
        usort($discounted_products, function($a, $b) {return $b['id'] <=> $a['id'];});
        
        if (empty($discounted_products)) {

            $discounted_products = db()->
                query("SELECT * FROM ". TABLE_NAME ." WHERE price = '' ORDER BY id DESC")->get();

        }

        if (!empty($discounted_products)) {

            return app()->view->full_view(
                
                CATEGORY_LAYOUT,
                'discount',
                [
                    'title'               => 'Купить японскую косметику со скидкой на Japan-in.Ru!',
                    'discounted_products' => $discounted_products,
                
                ],
            );
        
        }
        
        return app()->view->full_view (HOME_LAYOUT, HOME_VIEW, []);
    
    }


    static function delivery()
    {
        return app()->view->partial_view('views/delivery');
    
    }


}
