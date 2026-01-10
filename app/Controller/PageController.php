<?php
namespace App\Controller;
use Master\Pagination;

class PageController
{
    static function index()
    {
        if ( $data = db()->query("select * from categories")->get() ) {
            /* 
            $pagination = new Pagination();
                $limit = PAGINATION_SETTINGS['linesOnPage'];
                $data = db()->query("select * from categories limit $limit
                                        offset {$pagination->getOffset()}")->get();
            cache()->set('category', $category);   
            shuffle(products);
            */
            return app()->view->full_view (   
                PAGE_LAYOUT,    # layout
                PAGE_VIEW,      # view
                [               # data
                    //'title' => 'page',
                    //'category' => $data,
                    //'pagination' => $pagination,
                ],
            );        
        }
        return app()->view->full_view ( PAGE_LAYOUT, PAGE_VIEW, [] );
    }
}
