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
        /*
        $indexed_DB = db()->query("SELECT * FROM ". TABLE_NAME)->get();
        $cache_dir = 'cache_db';
        $cache_file = 'cache_db.json';
        $path = "/public/$cache_dir/$cache_file";

        if (!is_dir($cache_dir)) {
            mkdir($cache_dir, 0755, true);
        
        }
        //$json_data = file_get_contents($cache_file);
        //$products = json_decode($json_data, true);
        

        file_put_contents($_SERVER['DOCUMENT_ROOT'] .$path, json_encode($indexed_DB, JSON_UNESCAPED_UNICODE));
        */
        if ($discounted_products = db()->query(
            "SELECT * FROM ". TABLE_NAME ." WHERE price = ''")->get() // ORDER BY id DESC LIMIT 10")->get()
        
        ) {
            if (TABLE_NAME == 'cosmetics') {
                $title = 'JAPAN-IN.RU = Всё для Твоей красоты из Японии!';

            } else {
                $title = 'JAPAN-IN.RU = Всё для Твоей красоты и здоровья из Японии!';

            }

            return app()->view->full_view (

                HOME_LAYOUT,
                HOME_VIEW,
                [
                    'title'               => $title,
                    'discounted_products' => $discounted_products,
                ],
            );

        }
        return app()->view->full_view (HOME_LAYOUT, HOME_VIEW, []); //'title' = Japan-in.Ru',]);


    }


}
