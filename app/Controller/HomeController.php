<?php
namespace App\Controller;
use Master\Pagination;
use App\Cart\Cart;

class HomeController
{

    static function index()
    {

        //cache()->refreshCache();

        /*        
        dump(Cart::addToCart(11444));
        dump(Cart::getCart(11444));
        dump(session()-<?= base_url('>has('cart'));
        dump($_SESSION);
        dump(session()->get('cart.11444.slug'));
        dump(Cart::clearCart('cart.11444'));
        dump(session()->has('cart'));
        dump($_SESSION);
        */


        if ($all_products = cache()->getCache()) {
            // Фильтруем массив (аналог WHERE price = '')
            $discounted_products = array_filter($all_products, function($product) {
                return isset($product['price']) && $product['price'] === '';
            
            });

            // Если нужно ограничить количество и отсортировать (аналог LIMIT 10 и ORDER BY id DESC)
            // usort($discounted_products, fn($a, $b) => $b['id'] <=> $a['id']);
            // $discounted_products = array_slice($discounted_products, 0, 10);

            if (empty($discounted_products)) {
                $discounted_products = db()->query("SELECT * FROM ". TABLE_NAME ." WHERE price = ''")->get();
            
            }
            
            if (TABLE_NAME == 'cosmetics') {
                $title = 'JAPAN-IN.RU = Всё для Твоей красоты из Японии!';
        
            } else {
                $title = 'JAPAN-IN.RU = Всё для Твоей красоты и здоровья из Японии!';
            }

            return app()->view->full_view(
                HOME_LAYOUT,
                HOME_VIEW,
                [
                    'title'               => $title,
                    'discounted_products' => $discounted_products,
                
                ],
            );

        } else {
            return app()->view->full_view (HOME_LAYOUT, HOME_VIEW, []); //'title' = Japan-in.Ru',]);
        
        }   
    }

}
