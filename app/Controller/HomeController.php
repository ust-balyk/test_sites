<?php
namespace App\Controller;
use App\Cart\Cart;

class HomeController extends BaseController
{

    static function index()
    {
        cache()->refreshCache();

        self::init(); // запись URL

        if ($all_products = cache()->getCache_db()) {
            // аналог WHERE price = ''
            // dump($all_products['by_id']); тоесть во всех категориях
            $discounted_products = array_filter($all_products['by_id'], function($product) {
                return isset($product['price']) && $product['price'] === '' && $product['in_stock'] == 1;
            
            });

            // ограничить количество и отсортировать (аналог LIMIT 10 и ORDER BY id DESC)
            usort($discounted_products, fn($a, $b) => $b['id'] <=> $a['id']);
            $discounted_products = array_slice($discounted_products, 0, 8);
            
            if (empty($discounted_products)) {
                $discounted_products = db()->query("SELECT * FROM ". TABLE_NAME .
                    " WHERE price = '' AND in_stock = 1 ORDER BY id DESC LIMIT 8")->get();
            
            }
            
            $title = (TABLE_NAME == 'cosmetics') ? 'Японский уход и косметика — всё для твоей красоты!'
                : 'Японский уход, косметика и витамины — секреты твоей красоты!';

            return app()->view->full_view (

                HOME_LAYOUT,
                HOME_VIEW,
                [
                    'title'               => $title,
                    'discounted_products' => $discounted_products,
                
                ],
            );

        } else {
            return app()->view->full_view (HOME_LAYOUT, HOME_VIEW, []);
        
        }   
    }

}
