<?php
namespace App\Controller;
use Master\Pagination;
use App\Cart\Cart;


class PageController extends BaseController
{

    static function cosmetics() 
    {
        self::init(); // запись URL

        $cosmetics = cache()->getCache_db();
        $cosmetics = $cosmetics['by_id'];
 
        if (empty($cosmetics)) { 
            $cosmetics = db()->query("SELECT * FROM ". TABLE_NAME ." ORDER BY RAND()")->get();

        }

        return app()->view->full_view ( 
            
            CATEGORY_LAYOUT, 
            'cosmetics', 
            [
                'cosmetics' => $cosmetics,
                'title'     => '',
                'short_description' => '',
                'full_url'  => '',
                'image_url' => '',
                
            ] 
        ); 
        
        return app()->view->full_view (HOME_LAYOUT, HOME_VIEW, []); 
        
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
