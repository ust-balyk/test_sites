<?php
namespace App\Controller;
use App\Widgets\Cart\Cart;
use App\Helper\Text\Text;


class ProductController extends BaseController
{

    static function index()
    {
        self::init(); // запись URL

        [$slug, $id] = request()->getSLUG_or_ID() ?? [];
        
        $cache = cache()->getCache_db();
        $product = $cache['by_id'][$id] ?? null;
        
        if (empty($product) && !empty($id)) {
            $product = db()->query("SELECT * FROM " . TABLE_NAME . " WHERE outer_id = ? end in_stock = 1", 
                [$id])->get();
    
        }

        // Если товар найден (неважно, из кэша или базы)
        if (!empty($product)) {

            $arr_words = preg_split('~\s~', $product['title']);
            $product_title = '';
            foreach ($arr_words as $word) {
                if (preg_match_all('~\p{Latin}+(?:-\p{Latin}+)*~u', $word, $latin_matches)) {
                    $latin_word = array_shift($latin_matches[0]);
                    $product_title .= strtoupper($latin_word .' ');
                    unset($latin_matches[0]);
                } else {
                    $product_title .= mb_strtolower($word .' ');
                }
            }
            $title = 'Купить '. $product_title;
            $category_slug = $product['slug'];

            // Получаем похожие товары
            if (isset($cache['by_category'][$category_slug])) {
                $related_products = $cache['by_category'][$category_slug];
                if ($x = count($related_products)) { 
                    array_filter($related_products, fn($item) => $item['outer_id'] != $id);
                    shuffle($related_products);
                    $related_products = array_slice($related_products, 0, $x);
                
                } else {
                    // Если в кэше нет, берем из базы
                    $related_products = db()->query("SELECT * FROM " . TABLE_NAME . 
                        " WHERE slug = ? AND outer_id != ? ORDER BY RAND() LIMIT 8", 
                        [$category_slug, $id])->get() ?: [];

                }
                
            }
        

            /********* LD+JSON **********/
            // --------------------------------------------------
            // Безопасный host/протокол/URL
            // --------------------------------------------------
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $hostRaw  = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $host     = preg_replace('~[^a-zA-Z0-9-]~','', $hostRaw); //.\-:]/', '', $hostRaw);
            $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
            $fullUrl  = $protocol . '://' . $host . $requestUri;

            // --------------------------------------------------
            // Подготовка данных о товаре
            // --------------------------------------------------
            $productTitle = trim((string)($product['title'] ?? ''));
            $productTitleEsc = htmlspecialchars($productTitle, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

            $priceRaw = $product['price'] ?? $product['new_price'] ?? '';
            $cleanPrice = $priceRaw === '' ? '' : str_replace(',', '.', (string)$priceRaw);
            $cleanPrice = preg_replace('/[^\d.]/', '', $cleanPrice);
            $cleanPrice = rtrim($cleanPrice, '.');
            $numericPrice = $cleanPrice === '' ? 0 : (float)$cleanPrice;

            // image -> массив
            if (!empty($product['image']) && (strpos($product['image'], 
                'http://') === 0 || strpos($product['image'], 'https://') === 0)) {
                $imageUrl = $product['image'];
            } else {
                $imagePath = ltrim((string)($product['image'] ?? ''), '/');
                $imageUrl  = $protocol . '://' . $host . '/' . $imagePath;
            }
            $imageUrl = trim($imageUrl);
            $imageArray = $imageUrl === '' ? [] : [$imageUrl];

            // --------------------------------------------------
            // Короткое описание (meta description)
            // --------------------------------------------------
            $rawDescription = (string)($product['description'] ?? '');
            if ($rawDescription !== '') {
                $rawDescription = str_replace(['</p>', '</div>', '<br>', '<br/>'], ' ', $rawDescription);
                $rawDescription = strip_tags($rawDescription);
                $rawDescription = preg_replace('~[\r\n\xA0]+~u', ' ', $rawDescription);
                $rawDescription = trim(preg_replace('/\s+/', ' ', $rawDescription));
            }
            $limit = 160;
            if (mb_strlen($rawDescription) > $limit) {
                $tempString = mb_substr($rawDescription, 0, $limit);
                $lastSpace  = mb_strrpos($tempString, ' ');
                $shortDescription = $lastSpace !== false ? 
                    mb_substr($tempString, 0, $lastSpace) . '...' : mb_substr($tempString, 0, $limit) . '...';
            } else {
                $shortDescription = $rawDescription;
            }
            if (trim($shortDescription) === '') {
                $shortDescription = "Японская косметика {$productTitle}";
            }
            $shortDescription = trim($shortDescription);

            // --------------------------------------------------
            // Хлебные крошки
            // --------------------------------------------------
            $breadcrumbs = [
                ['name' => 'Главная',   'url' => $protocol . '://' . $host],
                ['name' => 'Косметика', 'url' => $protocol . '://' . $host . '/cosmetics'],
                [
                    'name' => $product['category'] ?? 'Категория',
                    'url'  => $protocol . '://' . $host . '/cosmetics/' . trim((string)($product['slug'] ?? ''), '/')
                ],
                ['name' => $productTitle, 'url' => $fullUrl],
            ];

            // --------------------------------------------------
            // Похожие товары (related products) — подготовка
            // --------------------------------------------------
            $relatedJson = [];
            if (!empty($related_products) && is_array($related_products)) {
                foreach (array_slice($related_products, 0, 8) as $rel) {
                    $rawRelPrice = $rel['price'] ?? $rel['new_price'] ?? '';
                    $relPriceStr = preg_replace('/[^\d.]/', '', (string)$rawRelPrice);
                    $relPriceStr = rtrim($relPriceStr, '.');
                    $relPrice = $relPriceStr === '' ? null : (float)$relPriceStr;

                    $relSlug = trim((string)($rel['slug'] ?? ''));
                    $relOuterId = trim((string)($rel['outer_id'] ?? ''));
                    $relUrl = $protocol . '://' . $host . '/cosmetics/' . $relSlug . '/product/' . $relOuterId;

                    $name = (string)($rel['title'] ?? '');
                    $name = preg_replace('/[\x00-\x1F\x7F]+/u', '', $name);
                    $name = mb_substr(trim($name), 0, 300);

                    $offer = [
                        '@type' => 'Offer',
                        'priceCurrency' => 'RUB'
                    ];
                    $offer['price'] = $relPrice === null ? null : $relPrice;

                    $relatedJson[] = [
                        '@id'   => $relUrl,
                        '@type' => 'Product',
                        'name'  => $name,
                        'url'   => $relUrl,
                        'offers'=> $offer
                    ];
                }
            }

            // --------------------------------------------------
            // aggregateRating — включать только если rating >= 4.0 и reviewCount > 0
            // --------------------------------------------------
            $aggregateRating = null;
            if (isset($ratingValue) && isset($reviewCount)) {
                $rv = (float)$ratingValue;
                $rc = (int)$reviewCount;
                if ($rc > 0 && $rv >= 4.0) {
                    $aggregateRating = [
                        '@type' => 'AggregateRating',
                        'ratingValue' => $rv,
                        'reviewCount' => $rc,
                        'bestRating' => 5
                    ];
                }
            }

            // --------------------------------------------------
            // Собираем итоговый массив JSON-LD
            // --------------------------------------------------
            $graph = [];

            $graph[] = [
                '@type' => 'BreadcrumbList',
                'itemListElement' => array_map(function($bc, $i) {
                    return [
                        '@type' => 'ListItem',
                        'position' => $i + 1,
                        'name' => (string)($bc['name'] ?? ''),
                        'item' => (string)($bc['url'] ?? '')
                    ];
                }, $breadcrumbs, array_keys($breadcrumbs))
            ];

            $productNode = [
                '@type' => 'Product',
                '@id' => rtrim($fullUrl, '/') . '#product',
                'name' => $productTitle,
                'image' => $imageArray,
                'description' => $shortDescription,
                'sku' => (string)($product['outer_id'] ?? ''),
                'brand' => [
                    '@type' => 'Brand',
                    'name' => (string)($product['brand'] ?? 'Japan Cosmetic')
                ],
                'countryOfOrigin' => [
                    '@type' => 'Country',
                    'name' => (string)($product['countryOfOrigin'] ?? 'Japan')
                ],
                'offers' => [
                    '@type' => 'Offer',
                    'url' => $fullUrl,
                    'priceCurrency' => 'RUB',
                    'price' => $numericPrice,
                    'itemCondition' => 'https://schema.org/NewCondition',
                    'availability' => ($numericPrice > 0) ? 
                    'https://schema.org/InStock' : 'https://schema.org/OutOfStock'
                ],
                'isRelatedTo' => $relatedJson
            ];

            if ($aggregateRating !== null) {
                $productNode['aggregateRating'] = $aggregateRating;
            }

            $graph[] = $productNode;

            $schemaData = [
                '@context' => 'https://schema.org',
                '@graph' => $graph
            ];

            // --------------------------------------------------

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
