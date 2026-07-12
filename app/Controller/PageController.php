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
        $all_cosmetics = $items_by_cache['by_id'] ?? [];
    
        // если нет в кэше — возьмём из БД
        if (empty($all_cosmetics)) {
            $items_by_db = db()->query("SELECT * FROM ". TABLE_NAME)->getAssoc('id');
            $all_cosmetics = $items_by_db ?? [];
        }

        if (! empty($all_cosmetics)) {
            // если нет сохранённого порядка — перемешиваем и сохраняем
            if (empty($_SESSION['shuffled_keys'])) {
                $all_cosmetics = shuffle_assoc($all_cosmetics); // внутри: $_SESSION['shuffled_keys'] = $keys;
            } else {
                $all_cosmetics = restore_order($all_cosmetics);
            }

            //dump($all_cosmetics);
            //
            $pagination = new Pagination();
            $cosmetics = array_slice(
                $all_cosmetics, $pagination->getOffset(), PAGINATION_SETTINGS['onPageRecords']
            );

            return app()->view->full_view(
                CATEGORY_LAYOUT,
                'cosmetics',
                [
                    'cosmetics'  => $cosmetics, 
                    'title'      => 'Купить японскую косметику со скидкой на Japan-in.Ru!', 
                    'short_description' => '', 
                    'full_url'   => '', 
                    'image_url'  => '',
                    'pagination' => $pagination,
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
                
                DEFAULT_LAYOUT,
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
