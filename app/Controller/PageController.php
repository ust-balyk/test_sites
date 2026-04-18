<?php
namespace App\Controller;
use Master\Pagination;
use App\Cart\Cart;


class PageController
{

    static function cosmetics() 
    { 

        $cosmetics = cache()->getCache();

        if (empty($cosmetics)) { 
            $cosmetics = db()->query("SELECT * FROM ". TABLE_NAME ." ORDER BY RAND()")->get(); 
            
        } 

        shuffle($cosmetics);

        return app()->view->full_view ( 
            CATEGORY_LAYOUT, 
            'cosmetics', 
            [ 
                'cosmetics' => $cosmetics[0], 
            
            ] 
        ); 
        
        return app()->view->full_view (HOME_LAYOUT, HOME_VIEW, []); 
    
    }



    static function category()
    {
        $slug = request()->get_SLUG_or_ID();
        $cache = cache()->getCache(); 
        $products = $cache['by_category'][$slug] ?? [];

        if (empty($products)) {
            $products = db()->query("SELECT * FROM " . TABLE_NAME . " WHERE slug = ?", [$slug])->get();
            
        }

        shuffle($products);
        return app()->view->full_view(
            CATEGORY_LAYOUT,
            CATEGORY_VIEW, 
            [
                'products' => $products,
                'category' => $products[0]['category'],
                'slug'     => $slug
            ]
        );

    }

    static function product()
    {
        $id = request()->get_SLUG_or_ID();
        $cache = cache()->getCache();
        $product = $cache['by_id'][$id] ?? null;
        $slug_product = $cache['by_id'][$id]['slug']; 
        
        if (!$product) {
            $product = db()->query("SELECT * FROM " . TABLE_NAME . " WHERE outer_id = ?", [$id])->get();
            $current_product = $product[0];
            $slug_category  = $current_product['slug'];
            $related_products = db()->query(
                "SELECT * FROM ". TABLE_NAME .
                " WHERE slug = ? AND outer_id != ? ORDER BY RAND() LIMIT 8", [$slug_category, $id])->get();
            return app()->view->full_view(
                PRODUCT_LAYOUT,
                PRODUCT_VIEW,
                [
                    'product'          => $current_product,
                    'related_products' => $related_products, // похожие продукты
                    
                ]
            );
            
        }

        $related_products = $cache['by_category'][$slug_product];
        $related_products = array_filter($related_products, fn($item) => $item['outer_id'] != $id);
        $related_products = array_values($related_products); // Сброс ключей
        shuffle($related_products);
        $related_products = array_slice($related_products, 0, 8); // например, только 8 штук
        
        return app()->view->full_view(
            PRODUCT_LAYOUT,
            PRODUCT_VIEW, 
            [
                'product' => $product,
                'related_products' => $related_products
            ]
        );
        
    }

    static function discount()
    {
        $all_products = cache()->getCache();

        $products = array_filter($all_products, function($product) {
            return isset($product['price']) && $product['price'] === '';
        });

        // ORDER BY id DESC
        usort($products, function($a, $b) {
            return $b['id'] <=> $a['id'];
        });

        if (empty($products)) {
            $products = db()->query("SELECT * FROM ". TABLE_NAME ." WHERE price = '' ORDER BY id DESC")->get();

        }

        return app()->view->full_view(
            PRODUCT_LAYOUT,
            'discount',
            [
                'discount' => 'title',
                'products' => $products,
            ],
        );
    
    }

    static function delivery()
    {
        return app()->view->partial_view('views/delivery');
    
    }


}
