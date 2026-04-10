<?php
namespace App\Controller;
use Master\Pagination;

class PageController
{

    static function cosmetics()
    {
        $cosmetics = db()->query("SELECT * FROM ". TABLE_NAME ." ORDER BY RAND()")->get();

        shuffle($cosmetics);

        return app()->view->full_view (

            CATEGORY_LAYOUT, 
            'cosmetics', 
            [ 
                'cosmetics' => $cosmetics, 
            
            ]

        );

    }

    static function category()
    {
        $slug = request()->get_ID_or_SLUG();
        //$products = [];
        //$category = '';
        //$slug = '';
            
        $products = db()->query("SELECT * FROM " . TABLE_NAME . " WHERE slug = ?", [$slug])->get();

        
        if (empty($products)) {

            $products = db()->query("SELECT * FROM ". TABLE_NAME ." WHERE price = ''")->get();
            /*
            $first_item = $products[0];         
            $category = 'товары по акции «тест-драйв качества»';
            $slug  = 'discount';
            shuffle($products);
            */
        } //else {
        
        $firstItem = $products[0];         
        $category = $firstItem['category'];
        $slug  = $firstItem['slug'];
        shuffle($products);

        //}

        return app()->view->full_view(
            CATEGORY_LAYOUT,
            CATEGORY_VIEW,
            [
                'products' => $products,
                'category' => $category,
                'slug'     => $slug,
            ]
        );

    }


    static function product()
    {
        $id = request()->get_ID_or_SLUG();

        // Запрос основного товара
        $product = db()->query("SELECT * FROM " . TABLE_NAME . " WHERE outer_id = ?", [$id])->get();

        if ($product) {
            $currentProduct = $product[0]; // Данные текущего товара
            
            $title_category = $currentProduct['category'] ?? null;
            $slug_category  = $currentProduct['slug'] ?? null;

            // Запрос ПОХОЖИХ товаров (из той же категории, исключая текущий)
            // Мы берем 4 случайных товара (ORDER BY RAND())
            $relatedProducts = db()->query(
                "SELECT * FROM ". TABLE_NAME . 
                " WHERE category = ? AND outer_id != ? ORDER BY RAND() LIMIT 8", [$title_category, $id])->get();
            /*
            // Если нашли меньше 8-х(указано в limit), добираем из общего списка
            if (count($related) < 8) {
                $needed = 8 - count($related);
                $extraIds = array_column($related, 'outer_id'); // Чтобы не дублировать уже найденные
                $extraIds[] = $id; // И чтобы не взять текущий товар

                $placeholders = implode(',', array_fill(0, count($extraIds), '?'));
                
                $extra = db()->query(
                    "SELECT * FROM " . TABLE_NAME . " WHERE outer_id NOT IN ($placeholders) ORDER BY RAND() LIMIT $needed",
                    $extraIds
                )->get();

                $related = array_merge($related, $extra);

                .related-products-grid {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 20px;
                    justify-content: center; /* Центрирует товары, если их 1, 2 или 3 *//*
                }
            }*/

            return app()->view->full_view(
                PRODUCT_LAYOUT,
                PRODUCT_VIEW,
                [
                    'product'          => $currentProduct,
                    'related_products' => $relatedProducts, // Массив для блока "Также покупают"
                    'title_category'   => $title_category,
                    'slug_category'    => $slug_category,
                ]
            );
        }
        return response()->redirect('/');

    }


    static function discount()
    {
        if ($products = db()->query("SELECT * FROM ". TABLE_NAME ." WHERE price = '' ORDER BY id DESC")->get()) {

            return app()->view->full_view (

                PRODUCT_LAYOUT,
                'discount',
                [
                    'discount' => 'title',
                    'products' => $products,

                ],
            );
        }
        response()->redirect();

    }

    static function delivery()
    {
        return app()->view->partial_view('views/delivery');
    
    }

}
