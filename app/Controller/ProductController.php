<?php
//declare(strict_types=1);
namespace App\Controller;
use App\Widgets\Cart\Cart;
use App\Helper\Text\Text;


class ProductController extends BaseController
{

    static function index()
    {
        self::init(); // запись URL

        [$slug, $id] = request()->getSLUG_or_ID() ?? [];
        $id         = (string)request()->getSLUG_or_ID()[1] ?? null;        
        $cache = cache()->getCache_db();
        $product = $cache['by_id'][$id] ?? null;
        
        if (empty($product) && !empty($id)) {
            $product = db()->query("SELECT * FROM " . TABLE_NAME . " WHERE outer_id = ? AND in_stock = 1", 
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

            $title = !empty($product_title) ? ('Купить '. $product_title) : 'Купить японскую косметику';

            $category_slug = $product['slug'];
            $related_products = $cache['by_category'][$category_slug] ?? [];

            if (!empty($related_products)) {
                // убрать текущий товар и товары вне наличия
                $related_products = array_values(array_filter(
                    $related_products,
                    fn($item) => ($item['outer_id'] ?? null) !== ($product['outer_id'] ?? null)
                            && (($item['in_stock'] ?? 0) == 1)
                ));
                //shuffle($related_products);
                shuffle_assoc($related_products);
                $related_products = array_slice($related_products, 0, 8);

                if (count($related_products) < 8) {
                    $needed = 8 - count($related_products);
                    $discounted_products = array_values(array_filter(
                        $cache['by_id'] ?? [],
                        fn($item) => ($item['outer_id'] ?? null) !== ($product['outer_id'] ?? null)
                                && !empty($item['new_price'])
                                && (($item['in_stock'] ?? 0) == 1)
                    ));
                    if (empty($discounted_products)) {
                        $discounted_products = db()->query(
                            "SELECT * FROM " . TABLE_NAME .
                            " WHERE outer_id != ? AND in_stock = 1 AND new_price IS NOT NULL 
                            AND new_price <> '' ORDER BY RAND() LIMIT 8",
                            [$product['outer_id']]
                        )->get() ?: [];
                    }
                    //shuffle($discounted_products);
                    shuffle_assoc($discounted_products);

                    // Добавляем, но индексируем по outer_id чтобы избежать дубликатов
                    $by_outer = [];
                    foreach ($related_products as $p) {
                        if (isset($p['outer_id'])) $by_outer[$p['outer_id']] = $p;
                    }
                    foreach ($discounted_products as $p) {
                        if (count($by_outer) >= 8) break;
                        if (!isset($p['outer_id']) || isset($by_outer[$p['outer_id']])) continue;
                        $by_outer[$p['outer_id']] = $p;
                    }
                    $related_products = array_values($by_outer);
                }
            }

            /********* LD+JSON **********/
            // --------------------------------------------------
            // Безопасный host/протокол/URL
            // --------------------------------------------------
            // Получаем и обрабатываем хост и путь
            $cacheKey = "host_request_uri_product_{$id}";
            $cachedData = cache()->get($cacheKey);

            if ($cachedData) {
                [$host, $requestUri] = $cachedData;
            } else {
                // Получаем 'сырой' хост (с портом, если есть)
                $hostRaw = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $host = preg_replace('~:\d+$~', '', $hostRaw); // Удаляем порт
                $host = preg_replace('~[^a-zA-Z0-9-]~', '', $host); // Удаляем недопустимые символы
                $host = strtolower($host); // Приводим к нижнему регистру

                // Если хост не валиден и не localhost, заменяем на localhost
                if (!filter_var($host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) && $host !== 'localhost') {
                    $host = 'localhost';
                }

                // Получаем и нормализуем путь
                $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
                $requestUri = '/' . ltrim(trim($requestUri), '/'); // Убираем дублирующиеся слеши

                // Кешируем результат на 1 час (3600 секунд)
                cache()->set($cacheKey, [$host, $requestUri], 3600);
            }

            // Формируем протокол (HTTP/HTTPS)
            $protocol = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
                ? 'https'
                : 'http';

            // Формируем полный URL
            $fullUrl = $protocol . '://' . $host . $requestUri;

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
            
            /*
            Из кэша (если кэш хранит рейтинг и количество отзывов):

            $reviewCount = (int)($cache['by_id'][$id]['review_count'] ?? 0);

            Из отдельного запроса (если данные о рейтингах хранятся в другой таблице):

            $reviewData = db()->query("SELECT rating, review_count FROM reviews WHERE product_id = ?", [$id])->get();
            $ratingValue = (float)($reviewData['rating'] ?? 0);
            $reviewCount = (int)($reviewData['review_count'] ?? 0);
            */

            // --------------------------------------------------
            // Получаем рейтинг и количество отзывов (если есть)
            // --------------------------------------------------
            $ratingValue = (float)($product['rating'] ?? 0);
            $reviewCount = (int)($product['review_count'] ?? 0);

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
                //shuffle($products);
                shuffle_assoc($products);
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
