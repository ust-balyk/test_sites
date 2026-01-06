<?php
namespace App\Controller;
use Master\Pagination;

class HomeController
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
        
        $data = db()->query("select * from users")->get();
        return app()->view->full_view (
            
            HOME_LAYOUT,  # layout

            HOME_VIEW,    # view
            
            [             # data
                
                'title' => 'japan-in.ru',
                'users' => $data,
                //'pagination' => $pagination,
            
            ]
        );
    
    }
    
}
