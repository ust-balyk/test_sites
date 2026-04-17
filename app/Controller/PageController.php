<?php
namespace App\Controller;
use Master\Pagination;
use App\Cart\Cart;


class PageController
{

    static function cosmetics() 
    { 
        if (file_exists($_SERVER['DOCUMENT_ROOT'] .'/cache_db/cache_db.json')) { 
            $json_data = file_get_contents($_SERVER['DOCUMENT_ROOT'] .'/cache_db/cache_db.json'); 
            $cosmetics = json_decode($json_data, true); 
            shuffle($cosmetics); 

            if (empty($cosmetics)) { 
                $cosmetics = db()->query("SELECT * FROM ". TABLE_NAME ." ORDER BY RAND()")->get(); 
            } 

            return app()->view->full_view ( 
                CATEGORY_LAYOUT, 
                'cosmetics', 
                [ 
                    'cosmetics' => $cosmetics[0], 
                ] 
            ); 
        } 
        return app()->view->full_view (HOME_LAYOUT, HOME_VIEW, []); 
    
    }



    static function category()
    {
        $slug = request()->get_SLUG_or_ID();
        $cache = cache()->loadCache(); 
        $products = $cache['by_category'][$slug] ?? [];

        if (empty($products)) {
            $products = db()->query("SELECT * FROM " . TABLE_NAME . " WHERE slug = ?", [$slug])->get();
            shuffle($products);
            return app()->view->full_view(
                CATEGORY_LAYOUT,
                CATEGORY_VIEW, 
                [
                    'products' => $products,
                    'category' => $products[0]['category'] ?? '',
                    'slug'     => $slug
                ]
            );
        }

        shuffle($products);
        return app()->view->full_view(
            CATEGORY_LAYOUT,
            CATEGORY_VIEW, 
            [
                'products' => $products,
                'category' => $products[0]['category'] ?? '',
                'slug'     => $slug
            ]
        );

    }

    static function product()
    {
        $id = request()->get_SLUG_or_ID();
        $cache = cache()->loadCache();
        $product = $cache['by_id'][$id] ?? null;
        
        if (!$product) {
            $product = db()->query("SELECT * FROM " . TABLE_NAME . " WHERE outer_id = ?", [$id])->get();
            $currentProduct = $product[0]; // Данные текущего товара
            //$title_category = $currentProduct['category'] ?? null;
            $slug_category  = $currentProduct['slug'] ?? null;
            // запрос ПОХОЖИХ товаров (из той же категории, исключая текущий)
            $relatedProducts = db()->query(
                "SELECT * FROM ". TABLE_NAME .
                " WHERE category = ? AND outer_id != ? ORDER BY RAND() LIMIT 8", [$slug_category, $id])->get();
            return app()->view->full_view(
                PRODUCT_LAYOUT,
                PRODUCT_VIEW,
                [
                    'product'          => $currentProduct,
                    'related_products' => $relatedProducts, // Массив для блока "похожие продукты"
                    //'title_category'   => $title_category,
                    //'slug_category'    => $slug_category,
                ]
            );

        }

        $category_slug = $product[3]['slug'];
        $related = $cache['by_category'][$category_slug] ?? [];

        // исключаем текущий товар из списка похожих
        $related = array_filter($related, function($item) use ($id) {
            return $item['id'] !== $id;
        });

        shuffle($related);
        $related = array_slice($related, 0, 4); // например, только 4 штуки

        return app()->view->full_view(
            PRODUCT_LAYOUT,
            PRODUCT_VIEW, 
            [
                'product' => $product,
                'related' => $related
            ]
        );

    }

    static function discount()
    {
        $all_products = cache()->loadCache();

        // Фильтруем (WHERE price = '')
        $products = array_filter($all_products, function($product) {
            return isset($product['price']) && $product['price'] === '';
        });

        // 3. Сортируем (ORDER BY id DESC)
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
