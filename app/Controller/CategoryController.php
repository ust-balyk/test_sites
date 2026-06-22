<?php
//declare(strict_types=1);
namespace App\Controller;
use App\Cart\Cart;
use Master\Pagination;

class CategoryController extends BaseController
{
    /*
    * Списки категорий и заголовков
    * ----------------------------------------------------------------- */
    private static array $categories = [
        "makeup"          => "декоративная косметика",
        "for-body"        => "для тела",
        "for-face"        => "для лица",
        "for-oral-cavity" => "для полости рта",
        "for-hair"        => "для волос",
        "for-hands"       => "для рук",
        "for-feet"        => "для ног",
        "aromatherapy"    => "ароматерапия",
        "gift-set"        => "подарочные наборы",
        "accessories"     => "аксессуары",
    ];

    private static array $titleExternal = [
        'makeup'           => "декоративная косметика",
        'for-oral-cavity'  => "средства по уходу за полостью рта",
        'gift-set'         => "косметика в подарочных наборах",
    ];

    private static array $titleInternal = [
        'makeup'           => "декоративная косметика",
        'for-body'         => "манящая безупречность тела",
        'for-face'         => "сияние твоего лица",
        'for-oral-cavity'  => "волшебство в улыбке",
        'for-hair'         => "блеск твоих волос",
        'for-hands'        => "очарование на кончиках пальцев",
        'for-feet'         => "притяжение походки",
        'gift-set'         => "косметика в подарочных наборах",
    ];

    /*
    * Точка входа
    * ----------------------------------------------------------------- */
    public static function index(): string
    {
        // При наличии init‑метода в BaseController вызываем его.
        if (method_exists(parent::class, 'init')) {
            self::init();
        }

        /* ------------------------------ */
        $slug         = (string)request()->getSLUG_or_ID()[0] ?? null;
        $all_products = [];

        // Попытка загрузить товары по запрошенному slug
        if ($slug) {
            $all_products = self::get_Products($slug);
        }

        // Если ничего не найдено – ищем первую/случайную непустую категорию
        if (empty($all_products)) {
            $slugs    = array_keys(self::$categories);
            $new_slug = $slugs[array_rand($slugs)];

            $all_products = self::get_Products($new_slug);
            if (!empty($all_products)) {
                $slug = $new_slug; // новый slug
            }
        }

        // Если всё‑равно пусто – главная страница
        if (empty($all_products)) {
            return app()->view->full_view(HOME_LAYOUT, HOME_VIEW, []);
        }

        /* -------------------------------------- */
        $pagination = new Pagination();
        $products = array_slice(
            $all_products, $pagination->getOffset(), PAGINATION_SETTINGS['onPageRecords']
        );

        /* --------------------------------------- */
        // SEO‑заголовок для категории
        $title = (TABLE_NAME === 'cosmetics')
            ? self::getTitle_External($slug) . ' на Japan-in.Ru'
            : 'Японский уход, косметика и витамины — секреты твоей красоты!';

        // Получаем и обрабатываем хост и путь
        $cacheKey = "host_request_uri_category_{$slug}";
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
        $full_url = $protocol . '://' . $host . $requestUri;


        // Краткое описание категории (можно задать статически или взять из первой карточки)
        $firstProduct   = $products[0];
        $rawDescription = (string)($firstProduct['description'] ?? '');
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
            $short_description = $lastSpace !== false
                ? mb_substr($tempString, 0, $lastSpace) . '...'
                : mb_substr($tempString, 0, $limit) . '...';
        } else {
            $short_description = $rawDescription;
        }
        if (trim($short_description) === '') {
            $short_description = "Японская косметика категории «" . (self::$categories[$slug] ?? $usedSlug) . "»";
        }
        $short_description = trim($short_description);

        // Хлебные крошки
        $breadcrumbs = [
            ['name' => 'Главная',   'url' => $protocol . '://' . $host],
            ['name' => 'Косметика', 'url' => $protocol . '://' . $host . '/cosmetics'],
            [
                'name' => $slug ?? 'Категория',
                'url'  => $protocol . '://' . $host . '/cosmetics/' . $slug,
            ],
        ];

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

        // JSON‑LD: BreadcrumbList + ItemList
        $itemList_elements = [];
        $position = 1;
        foreach ($products as $item) {
            $slug = $item['slug'] ?? '';
            $url  = $protocol .'://'. $host .'/cosmetics/'. $slug .'/'. $item['outer_id'];
            $price = (int)($item['price'] ?: $item['new_price']);
            $description = $item['title'] ?? '';
            $image_url = $protocol . '://' . $host . $item['image'] ?? '';

            $itemList_elements[] = [
                '@type'       => 'ListItem',
                'position'    => $position++,
                'url'         => $url,
                'item'        => [
                    '@type'  => 'Product',
                    'name'   => $description,
                    'image'  => $image_url,
                    'offers' => [
                        '@type'         => 'Offer',
                        'name'          => $description,
                        'url'           => $url,
                        'priceCurrency' => 'RUB',
                        'price'         => $price ?? '',
                        'availability'  => (!empty($item['in_stock']) && $item['in_stock'])
                            ? 'https://schema.org/InStock'
                            : 'https://schema.org/OutOfStock',
                    ],
                    'aggregateRating' => $item['aggregate_rating'] ?? null,
                ],
            ];
        }

        $graph = [];

        // BreadcrumbList
        $graph[] = [
            '@type'           => 'BreadcrumbList',
            'itemListElement' => array_map(
                static function (array $bc, int $i) {
                    return [
                        '@type'    => 'ListItem',
                        'position' => $i + 1,
                        'name'     => $bc['name'] ?? '',
                        'item'     => [
                            '@id' => $bc['url'] ?? '',
                        ],
                    ];
                },
                $breadcrumbs,
                array_keys($breadcrumbs)
            ),
        ];

        // ItemList – список товаров в категории
        $graph[] = [
            '@type'           => 'ItemList',
            'description'     => self::getTitle_External($slug),
            'name'            => $title,
            'url'             => $full_url,
            'numberOfItems'   => count($products),
            'itemListElement' => $itemList_elements,
        ];

        $schema_data = [
            '@context' => 'https://schema.org',
            '@graph'   => $graph,
        ];

        /* передача данных во view */
        return app()->view->full_view(
            CATEGORY_LAYOUT,
            CATEGORY_VIEW,
            [
                'full_url'          => $full_url,
                'breadcrumbs'       => $breadcrumbs,
                'short_description' => $short_description,
                'schema_data'       => $schema_data,
                'title'             => mb_ucfirst($title),
                'products'          => $products,
                'category'          => $firstProduct['category'] ?? '',
                'category_title'    => self::getTitle_Internal($firstProduct['slug']),
                'slug'              => $slug,
                'image_url'         => $image_url,
                'pagination'        => $pagination,
            ]
        );
    }

    /* ----------------------------------------------------------------- */

    /*
    * Получить товары из кеша (или из БД, если кеш пуст)
    * -------------------------------------------------------- */
    private static function get_Products(string $slug): array
    {
        $cache    = cache()->getCache_db();
        $products = $cache['by_category'][$slug] ?? [];

        if (empty($products)) {
            $products = db()
                ->query(
                    'SELECT * FROM ' . TABLE_NAME . ' WHERE slug = ?',
                    [$slug]
                )
                ->get();

            if (!empty($products)) {
                cache()->refresh_Cache();
            }
        }

        return $products;
    }

    /*
    *   SEO‑заголовок (внешний) для категории
    * ------------------------------------------------------------ */
    private static function getTitle_External(string $key): string
    {
        if (isset(self::$titleExternal[$key])) {
            return self::$titleExternal[$key];
        }

        if (!isset(self::$categories[$key])) {
            return 'косметика';
        }

        $categoryName = self::$categories[$key];
        $link         = self::getLink_Word($key, $categoryName);

        return 'косметика' . ($link !== '' ? " {$link} " : ' ') . $categoryName;
    }

    /*
    *   Внутренний заголовок
    * ------------------------------------------------------------- */
    private static function getTitle_Internal(string $key): string
    {
        if (isset(self::$titleInternal[$key])) {
            return self::$titleInternal[$key];
        }

        if (!isset(self::$categories[$key])) {
            return 'косметика';
        }

        $categoryName = self::$categories[$key];
        $link         = self::getLink_Word($key, $categoryName);

        return 'косметика' . ($link !== '' ? " {$link} " : ' ') . $categoryName;
    }

    /*
    *   Возвращает предлог/союз, используемый в заголовках
    * ---------------------------------------------------------------------------- */
    private static function getLink_Word(string $key, string $categoryName): string
    {
        if ($key === 'aromatherapy' || $key === 'accessories') {
            return 'и';
        }

        // Если название уже начинается с "для" – предлог не нужен
        if (strpos($categoryName, 'для') === 0) {
            return '';
        }

        return 'для';
    }

    
    private static function seeded_shuffle(array $arr, int $seed): array
    {
        $originalKeys = array_keys($arr);
        $values = array_values($arr);

        $n     = count($values);
        $state = $seed;

        $rand = static function (int $min, int $max) use (&$state): int {
            $state = (1103515245 * $state + 12345) & 0x7FFFFFFF;
            return $min + ($state % ($max - $min + 1));
        };

        for ($i = $n - 1; $i > 0; $i--) {
            $j = $rand(0, $i);
            [$values[$i], $values[$j]] = [$values[$j], $values[$i]];
        }

        if (!empty($originalKeys)) {
            $shuffled = [];
            foreach ($values as $index => $value) {
                $shuffled[$originalKeys[$index]] = $value;
            }
            return $shuffled;
        }

        return $values;
    }

}

