<?php
namespace App\Controller;
use Master\Pagination;


class HomeController
{

    static function index()
    {
        if ($sale_items = db()->query("SELECT * FROM ". TABLE_NAME ." WHERE price = '' ORDER BY id DESC LIMIT 10")->get()) {

            if (TABLE_NAME == 'cosmetics') {
                $title = 'Japan-in.Ru — Всё для Твоей красоты из Японии!';

            } else {
                $title = 'Japan-in.Ru — Всё для Твоей красоты и здоровья из Японии!';

            }

            return app()->view->full_view (

                HOME_LAYOUT,
                HOME_VIEW,
                [
                    'title'      => $title,
                    'sale_items' => $sale_items,
                ],

            );
        }
        return app()->view->full_view (HOME_LAYOUT, HOME_VIEW, []); //'title' => 'NO PRODUCTS FOUND',]);

    }


}
