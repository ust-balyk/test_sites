<?php
namespace App\Controller;
use Master\Pagination;
use App\Cart\Cart;


class PageController
{

    static function cosmetics() 
    { 
        
        $cosmetics = cache()->getCache();
        shuffle($cosmetics);

        return app()->view->full_view ( 
            CATEGORY_LAYOUT, 
            'cosmetics', 
            [ 
                'cosmetics' => $cosmetics[0], 
            
            ] 
        );

        if (empty($cosmetics)) { 
            $cosmetics = db()->query("SELECT * FROM ". TABLE_NAME ." ORDER BY RAND()")->get();

            return app()->view->full_view ( 
                CATEGORY_LAYOUT, 
                'cosmetics', 
                [ 
                    'cosmetics' => $cosmetics, 
                
                ] 
            ); 
        }
        return app()->view->full_view (HOME_LAYOUT, HOME_VIEW, []); 
    
    }



    static function category()
    {
        if ($slug = request()->get_SLUG_or_ID()[0]) {
            //[$slug] = request()->get_SLUG_or_ID();
            //[$slug, $id] = request()->get_SLUG_or_ID();
            $cache = cache()->getCache(); 
            $products = $cache['by_category'][$slug] ?? [];
        
            //$product = null; //test

            if (empty($products)) {
                $products = db()->query("SELECT * FROM " . TABLE_NAME . " WHERE slug = ?", [$slug])->get();
            
            }
            shuffle($products);

            return app()->view->full_view(
                CATEGORY_LAYOUT,
                CATEGORY_VIEW, 
                [
                    'products' => $products,
                    'category' => $products[0]['category'] ?? 'каталог',
                    'slug'     => $slug
                ]
            );

        }

        return app()->view->full_view (HOME_LAYOUT, HOME_VIEW, []);

    }

    /*
    static function product()
    {
        [$slug, $id] = request()->get_SLUG_or_ID();
        $cache = cache()->getCache();
        $product = $cache['by_id'][$id] ?? null;

        if (empty($product)) {
            $product = db()->query("SELECT * FROM " . TABLE_NAME . " WHERE outer_id = ?", [$id])->get();

            if (!empty($product)) {
                $current_product = $product[0];
                $slug_category  = $current_product['slug'];
                $related_products = db()->query("SELECT * FROM ". TABLE_NAME .
                    " WHERE slug = ? AND outer_id != ? ORDER BY RAND() LIMIT 8", [$slug_category, $id])->get();
                
                return app()->view->full_view(
                    PRODUCT_LAYOUT,
                    PRODUCT_VIEW,
                    [
                        'product'          => $current_product,
                        'related_products' => $related_products, // похожие продукты
                    
                    ]
                );  
            
            } else {
                $products = $cache['by_category'][$slug] ?
                    : db()->query("SELECT * FROM " . TABLE_NAME . " WHERE slug = ?", [$slug])->get() ?
                        : [];

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
        }

        $slug_category = $cache['by_id'][$id]['slug']; 
        $related_products = $cache['by_category'][$slug_category];
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
        
    }*/
    
    static function product()
    {
        [$slug, $id] = request()->get_SLUG_or_ID();
        $cache = cache()->getCache();
        
        // Пытаемся найти товар (сначала кэш, потом база)
        $product = $cache['by_id'][$id] ?? null;

        if (empty($product) && !empty($id)) {
            // Используем ?: так как get() возвращает false при неудаче
            $result = db()->query("SELECT * FROM " . TABLE_NAME . " WHERE outer_id = ?", [$id])->get();
            $product = $result ? $result[0] : null;
        }

        // Если товар найден (неважно, из кэша или базы)
        if (!empty($product)) {
            $category_slug = $product['slug'];
            
            // Получаем похожие товары
            if (isset($cache['by_category'][$category_slug])) {
                $related = $cache['by_category'][$category_slug];
                $related = array_filter($related, fn($item) => $item['outer_id'] != $id);
                shuffle($related);
                $related = array_slice($related, 0, 8);
            } else {
                // Если в кэше нет, берем из базы
                $related = db()->query("SELECT * FROM " . TABLE_NAME . 
                    " WHERE slug = ? AND outer_id != ? ORDER BY RAND() LIMIT 8", 
                    [$category_slug, $id])->get() ?: [];
            }

            return app()->view->full_view(PRODUCT_LAYOUT, PRODUCT_VIEW, [
                'product'          => $product,
                'related_products' => $related,
            ]);
        }

        // Если товара нет, но есть SLUG — показываем категорию
        if (!empty($slug)) {
            $products = $cache['by_category'][$slug] ?
                : db()->query("SELECT * FROM " . TABLE_NAME . " WHERE slug = ?", [$slug])->get() ?
                    : [];

            if (empty($products)) {
                // Если и категории нет — 404 или редирект
                header("Location: /cosmetics", true, 302);
                exit;
                //response()->redirect('/');
            }

            shuffle($products);

            return app()->view->full_view(CATEGORY_LAYOUT, CATEGORY_VIEW, [
                'products' => $products,
                'category' => $products[0]['category'] ?? 'Каталог',
                'slug'     => $slug
            ]);
        }

        //return app()->view->full_view (HOME_LAYOUT, HOME_VIEW, []);
        // Запасной редирект
        //header("Location: /cosmetics", true, 302);
        //exit;
    }

    static function discount()
    {
        $all_products = cache()->getCache();
        $products = array_filter($all_products, function($product) {
            return isset($product['price']) && $product['price'] === '';

        });
        // ORDER BY id DESC
        usort($products, function($a, $b) {return $b['id'] <=> $a['id'];});
        
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
