<?php
namespace App\Controller;
use Master\Pagination;

class PageController
{

    static function category()
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
                CATEGORY_LAYOUT,    # layout
                CAYEGORY_VIEW,      # view
                [               # data
                    //'title' => 'page',
                    //'category' => $data,
                    //'pagination' => $pagination,
                ],
            );        
        
        }
        return app()->view->full_view ( CATEGORY_LAYOUT, CATEGORY_VIEW, [] );
    
    }

    static function product()
    {

        if ( $data = db()->query("select * from categories")->get() ) {

            return app()->view->full_view (
                PRODUCT_LAYOUT,
                PRODUCT_VIEW,
                [],
            );
        
        }
        return app()->view->full_view ( PRODUCT_LAYOUT, PRODUCT_VIEW, [] );

    }


}
