<?php
namespace App\Controller;
use Master\Pagination;

class HomeController
{
    static function index()
    {
        /*
        if ( ! $data = db()->query("select * from categories")->get() ) {
            return app()->view->full_view( "", "", [] );

        } else {
            /*
            $pagination = new Pagination();
                $limit = PAGINATION_SETTINGS['linesOnPage'];
                $data = db()->query("select * from categories limit $limit
                                        offset {$pagination->getOffset()}")->get();
            cache()->set('cache', $categories);
            */
            return app()->view->full_view (
                HOME_LAYOUT,  # layout
                HOME_VIEW,    # view
                [             # data    
                    //'title'      => 'home::japan-in.ru',
                    //'categories' => $data,
                    //'cache'      => $categories,
                    //'pagination' => $pagination,
                ],
            );
        //}
    }
}
