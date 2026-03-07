<?php
namespace App\Controller;
use Master\Pagination;

class HomeController
{
    static function index()
    {
        if ( $data = db()->query("select * from products")->get() ||
                $data = db()->query("select * from cosmetics")->get() ) {
            
            return app()->view->full_view (

                HOME_LAYOUT,
                HOME_VIEW,
                [],

            );
        }
        return app()->view->full_view ( HOME_LAYOUT, HOME_VIEW, [] );
    
    }

}
