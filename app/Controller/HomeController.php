<?php
namespace App\Controller;
use Master\Pagination;


class HomeController
{

    static function index()
    {
        if ($discounted_products = db()->query(
            "SELECT * FROM ". TABLE_NAME ." WHERE price = ''")->get() // ORDER BY id DESC LIMIT 10")->get()
        
        ) {
            if (TABLE_NAME == 'cosmetics') {
                $title = 'Japan-in.Ru — Всё для Твоей красоты из Японии!';

            } else {
                $title = 'Japan-in.Ru — Всё для Твоей красоты и здоровья из Японии!';

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
