<?php
namespace App\Controller;
use App\Widgets\Cart\Cart;
use App\Helper\Text\Text;


class ProductController extends BaseController
{

    static function index()
    {
        self::init(); // запись URL

        [$slug, $id] = request()->get_SLUG_or_ID();
        $cache = cache()->getCache_db();
        
        // Пытаемся найти товар (сначала кэш, потом база)
        $product = $cache['by_id'][$id] ?? null;
        
        if (empty($product) && !empty($id)) {
            // Используем ?: так как get() возвращает false при неудаче
            $result = db()->query("SELECT * FROM " . TABLE_NAME . " WHERE outer_id = ?", [$id])->get();
            $product = $result ? $result[0] : null;
    
        }

        // Если товар найден (неважно, из кэша или базы)
        if (!empty($product)) {

            $title = 'Купить '. mb_ucfirst($product['title']);
            //$description = Text::clean($product['description']);
            
            $category_slug = $product['slug'];

            // Получаем похожие товары
            if (isset($cache['by_category'][$category_slug])) {
                $related_products = $cache['by_category'][$category_slug];
                $related_products = array_filter($related_products, fn($item) => $item['outer_id'] != $id);
                shuffle($related_products);
                $related_products = array_slice($related_products, 0, 8);
            } else {
                // Если в кэше нет, берем из базы
                $related_products = db()->query("SELECT * FROM " . TABLE_NAME . 
                    " WHERE slug = ? AND outer_id != ? ORDER BY RAND() LIMIT 8", 
                    [$category_slug, $id])->get() ?: [];
            }
            // --------------------------------------------------
            // Формируем базовый URL
            // --------------------------------------------------
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host     = $_SERVER['HTTP_HOST'];
            $fullUrl  = $protocol . '://' . $host . $_SERVER['REQUEST_URI'];

            // --------------------------------------------------
            // Подготовка данных о товаре
            // --------------------------------------------------
            $productTitle   = htmlspecialchars($product['title'] ?? '');
            $priceRaw       = $product['price'] ?? $product['new_price'] ?? '';
            $cleanPrice     = $priceRaw !== '' ? $priceRaw : '0';
            $cleanPrice     = str_replace(',', '.', $cleanPrice);           // поддержка запятой
            $numericPrice   = preg_replace('/[^0-9.]/', '', $cleanPrice);

            if (!empty($product['image']) && strpos($product['image'], 'http') === 0) {
                $imageUrl = $product['image'];
            } else {
                $imagePath = ltrim($product['image'] ?? '', '/');
                $imageUrl  = $protocol . '://' . rtrim($host, '/') . '/' . $imagePath;
            }

            // --------------------------------------------------
            // Описание для соцсетей (meta description)
            // --------------------------------------------------
            $rawDescription = $product['description'] ?? '';
            if ($rawDescription) {
                $rawDescription = str_replace(['</p>', '</div>', '<br>', '<br/>'], ' ', $rawDescription);
                $rawDescription = strip_tags($rawDescription);
                $rawDescription = preg_replace('~[\r\n\xA0]+~u', ' ', $rawDescription);
                $rawDescription = trim(preg_replace('/\s+/', ' ', $rawDescription));
            }

            $limit = 160;
            if (mb_strlen($rawDescription) > $limit) {
                $tempString = mb_substr($rawDescription, 0, $limit);
                $lastSpace  = mb_strrpos($tempString, ' ');
                $shortDescription = mb_substr($tempString, 0, $lastSpace) . '...';
            } else {
                $shortDescription = $rawDescription;
            }
            if (empty($shortDescription)) {
                $shortDescription = "Японская косметика {$productTitle} с доставкой.";
            }

            // --------------------------------------------------
            // Хлебные крошки
            // --------------------------------------------------
            $breadcrumbs = [
                ['name' => 'Главная',    'url' => $protocol . '://' . $host],
                ['name' => 'Косметика',  'url' => $protocol . '://' . $host . '/cosmetics'],
                [
                    'name' => $product['category'] ?? 'Категория',
                    'url'  => $protocol . '://' . $host . '/cosmetics/' . trim($product['slug'], '/')
                ],
                ['name' => $productTitle, 'url' => $fullUrl],
            ];

            // --------------------------------------------------
            // Похожие товары (JSON‑LD)
            // --------------------------------------------------
            $relatedJson = [];
            if (!empty($related_products)) {
                foreach (array_slice($related_products, 0, 8) as $rel) {
                    $relPrice = preg_replace('/[^0-9.]/', '', $rel['price'] ?? $rel['new_price'] ?? '0');

                    $relUrl = $protocol .'://'. $host .'/cosmetics/'. $rel['slug'] .'/product/'. $rel['outer_id'];

                    $relatedJson[] = [
                        '@id'   => $relUrl,                     // уникальный идентификатор
                        '@type' => 'Product',
                        'name'  => $rel['title'],
                        'url'   => $relUrl,
                        'offers' => [
                            '@type'        => 'Offer',
                            'price'        => $relPrice,
                            'priceCurrency'=> 'RUB'
                        ]
                    ];
                }
            }

            // --------------------------------------------------
            // Общая разметка JSON‑LD (Schema.org)
            // --------------------------------------------------
            $schemaData = [
                '@context' => 'https://schema.org',
                '@type'    => 'Product',
                'name'     => $product['title'] ?? '',
                'image'    => $imageUrl,
                'description' => $shortDescription,
                'sku'      => $product['outer_id'] ?? '',
                'offers'   => [
                    '@type'         => 'Offer',
                    'url'           => $fullUrl,
                    'priceCurrency' => 'RUB',
                    'price'         => $numericPrice,
                    'availability'  => 'https://schema.org/InStock'
                ],
                'isRelatedTo' => $relatedJson
            ];

            return app()->view->full_view(PRODUCT_LAYOUT, PRODUCT_VIEW, [
                'fullUrl'          => $fullUrl,
                'breadcrumbs'      => $breadcrumbs,
                'imageUrl'         => $imageUrl,
                'productTitle'     => $productTitle,
                'shortDescription' => $shortDescription,
                'priceRaw'         => $priceRaw,
                'cleanPrice'       => $cleanPrice,
                'numericPrice'     => $numericPrice,
                'relatedJson'      => $relatedJson,
                'schemaData'       => $schemaData,
                'title'            => $title,
                //'description'      => $description,
                'product'          => $product,
                'related_products' => $related_products,

            ]);
        }

        // Если товара нет, но есть SLUG — показываем категорию
        if (!empty($slug) && empty($product)) {

            $products = $cache['by_category'][$slug] ?
                : db()->query("SELECT * FROM " . TABLE_NAME . " WHERE slug = ?", [$slug])->get() ?: [];
            
            if($products) {
                shuffle($products);
                return app()->view->full_view(CATEGORY_LAYOUT, CATEGORY_VIEW, [
                    'products' => $products,
                    'category' => $products[0]['category'] ?? 'Каталог',
                    'slug'     => $slug
                ]);
            }
        } else {
            // если ничего не найдено
            header("Location: /cosmetics", true, 302);
            exit;

        }
    }


}
