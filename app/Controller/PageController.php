<?php
namespace App\Controller;
use Master\Pagination;

class PageController
{
    static function index()
    { 
        /*
        $pagination = new Pagination();
        
        $limit = PAGINATION_SETTINGS['linesOnPage'];
        
        $data = db()->query("select * from users limit $limit
                        offset {$pagination->getOffset()}")->get();
         */
        //cache()->set('users', $users);   
        //shuffle($users);
        return app()->view->full_view (
            
            PAGE_LAYOUT,    # layout

            PAGE_VIEW,      # view
            
            [               # data
                
                'title' => 'page',
                //'users' => $data,
                //'pagination' => $pagination,
            
            ]
        );

    }
    
}
