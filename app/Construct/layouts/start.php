<!DOCTYPE html>
<html lang="ru" class="notranslate">
  <head>
    <base href="<?= base_url('/'); ?>">
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= hsc(session()->get('csrf_token')) ?>">
    <!--meta name="csrf-token" content="ffcc7dd51ec868bcb0d325b90217a5f91d96314b5bb"-->
    <meta name="referrer" content="no-referrer-when-downgrade">
    <meta name="robots" content="index, follow">
    <title><?= $title ?? 'Японский уход и косметика на Japan-in.Ru'; ?></title>
    <link rel="preload" href="<?= base_url(POCKET_STYLE.'/assets/banner/banner.webp'); ?>" as="image">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Japan-in.Ru : Японская косметика по доступной цене!">
    <meta name="keywords" content="косметика, япония, секреты красоты">
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="<?=base_url(POCKET_STYLE.'/favicon/icon.png');?>" type="image/png">
    <link rel="preload" href="<?=base_url(POCKET_STYLE.'/font/sfpro_text/SFProText-Regular.woff2');?>" 
          as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="<?=base_url(POCKET_STYLE.'/font/sfpro_text/SFProText-Medium.woff2');?>" 
          as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('/library/fontawesome/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('/library/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('/library/js/jquery-ui.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('/library/owlcarousel/owl.carousel.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('/library/owlcarousel/owl.theme.default.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url(POCKET_STYLE.'/css/main.css'); ?>">
    <link rel="stylesheet" href="<?= base_url(POCKET_STYLE.'/css/media.css'); ?>">
    <meta property="og:locale" content="ru_RU" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Japan-in.Ru — всё для Твоей красоты!" />
    <meta property="og:description" content="Инновационные достижения косметологии из Страны восходящего солнца!" />
    <meta property="og:url" content="https://japan-in.ru" />
    <meta property="og:image" content="https://japan-in.ru/public/japan-in-ru.jpg" />
    <meta property="og:image:width" content="1630" />
    <meta property="og:image:height" content="1136" />
    <meta property="og:image:type" content="image/jpg" />
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "OnlineStore",
      "@id": "https://japan-in.ru/#store",
      "name": "Japan-in-Ru : Магазин японской косметики",
      "areaServed": "RU",
      "url": "https://japan-in.ru",
      "logo": "https://japan-in.ru/public/japan-in-ru.png",
      "description": "Поставщик премиальной японской косметики",
      "address": {
        "@type": "PostalAddress",
        "addressCountry": "RU"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+79124174818",
        "contactType": "customer service",
        "availableLanguage": [
          {
            "@type": "Language",
            "name": "Russian",
            "alternateName": "ru"
          }
        ]
      },
      "knowsAbout": [
        "Японская косметика",
        "Уход за кожей лица",
        "Натуральная косметика",
        "Органическая косметика",
        "Q-beauty",
        "Антивозрастной уход",
        "Увлажнение кожи",
        "Восстановление барьерной функции кожи",
        "Минималистичный уход (скинимализм)",
        "Гибридные средства (уход + макияж)",
        "Косметика с SPF",
        "Профессиональная косметика",
        "Уход за волосами",
        "Красивые ногти",
        "Тренды красоты",
        "Безопасная косметика"
      ],
      "sameAs": [
        "https://wa.me/79124174818",
        "https://t.me/japan_in_ru"
      ]
    }
    </script>
      <!--style>
      .navbar .cart-wrapper { display:flex; align-items:center; gap:.5rem; }
      @media (max-width: 991.98px) {
        .navbar .cart-wrapper { order: 3; }
      }
      .dropdown-menu.cart-menu { min-width: 320px; max-width: calc(100vw - 2rem); }
    </style-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> 
  </head>
  <body>
    <div style="height:1px;background:#a9ddf9;border-bottom:1px dotted #744474;clear:both"></div>
    <div class="wrapper">
      <!---------------- HEADER ---------------->
      <header>
        <div class="banner">
          <img class="banner-img" src="<?= base_url(POCKET_STYLE .'/assets/banner/banner.webp'); ?>"
              alt="настоящая японская косметика">
        </div>
        <nav class="navbar navbar-expand-lg navbar-light fixed-top nav-shadow">
          <div class="container-fluid position-relative">
            <a class="navbar-brand" href="<?= base_url('/home'); ?>">
              <img class="brand" src="<?= base_url(POCKET_STYLE .'/favicon/home.png'); ?>">
            </a>
            <div class="collapse navbar-collapse" id="navbarMain">
              <ul class="navbar-nav me-auto mb-2 mb-lg-0"> 
                <li class="nav-item">
                  <a class="nav-link" href="#about_us">О нас</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="tel:+79124174818">+7(912)4174818</a>
                </li>
                <li class="nav-item dropdown delivery_and_payment">
                  <a class="nav-link dropdown-toggle" href="#" id="delivery_payment"
                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Условия доставки
                  </a>

                  <?php new App\Widgets\Delivery\Delivery(); ?>

                </li>
                <li class="nav-item dropdown has-megamenu">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown"
                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                    style="font-weight:600">Каталог
                  </a>

                  <?php new App\Widgets\Menu\Menu(); ?>

                </li>
              </ul>
            </div>
            <!--ul class="navbar-nav d-flex align-items-center gap-3 mb-2 mb-lg-0">
              <li class="nav-item dropdown user">
                <?php if (empty(session()->get('user.name'))) { ?>
                <a href="/login">
                  <img class="ico" id="user" src="<?= base_url(POCKET_STYLE .'/favicon/user_add.png'); ?>"/>
                </a>
                <?php } else { echo user_icon(); session()->set('return_to', $_SERVER['HTTP_REFERER'] ?? '/'); } ?>
              </li>
              <li class="nav-item dropdown wishlist">
                <?php if (empty(session()->get('user.name'))) { ?>
                <a href="#" class="nav-link dropdown-toggle" id="wishlist"
                  role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <img class="ico" src="<?= base_url(POCKET_STYLE .'/favicon/heart.png'); ?>">
                </a>
                <?php } else { echo user_heart(); } ?>

                <?php new App\Widgets\Cart\Cart(); ?>

              </li>
              <li class="nav-item dropdown cart ms-1">
                <?php if (empty(session()->get('user.name'))) { ?>
                <a href="#" class="nav-link dropdown-toggle" id="cart"
                  role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <img class="ico" src="<?= base_url(POCKET_STYLE .'/favicon/cart.png'); ?>">
                </a>
                <?php } else { echo user_cart(); } ?>

                <?php new App\Widgets\Cart\Cart(); ?>

              </li>
              <button id="theme-toggle" class="theme-btn" aria-label="Переключить тему" style="margin-right:2.2rem">
                <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                  viewBox="0 0 24 24" fill="none" stroke="#4d4d4d" stroke-width="1.6"
                  stroke-linecap="round" stroke-linejoin="round" aria-hidden="false">
                  <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
              </button>
            </ul-->
        <!--nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom fixed-top nav-shadow"-->



          <!--div class="container-fluid position-relative"-->
            <!--a class="navbar-brand me-3" href="#">Brand</a-->

            <!-- Навигация (прижата влево) -->
            <!--div class="collapse navbar-collapse" id="navbarMain">
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" href="#">Главная</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Каталог</a></li>
                <li class="nav-item"><a class="nav-link" href="#">О нас</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Контакты</a></li>
              </ul>
            </div>

            <!-- Гамбургер — центрируется на малых экранах -->
            <button class="navbar-toggler center-toggler" type="button" 
                  data-bs-toggle="collapse" data-bs-target="#navbarMain"
                aria-controls="navbarMain" aria-expanded="false" aria-label="Переключить навигацию">
              <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Иконка корзины — справа, всегда поверх -->
            <div class="cart-wrap">
              <ul class="d-flex align-items-center gap-3 mb-2 mb-lg-0" style="list-style: none;">
                <li>
                  <?php if (empty(session()->get('user.name'))) { ?>
                  <a href="/login">
                    <img class="ico" id="user" src="<?= base_url(POCKET_STYLE .'/favicon/user_add.png'); ?>"/>
                  </a>
                  <?php } else { echo user_icon(); } ?>
                </li>
                <li class="ms-2">
                  <?php if (empty(session()->get('user.name'))) { ?>
                  <a href="#" class="nav-link dropdown-toggle" id="wishlist"
                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="ico" src="<?= base_url(POCKET_STYLE .'/favicon/heart.png'); ?>">
                  </a>
                  <?php } else { echo user_heart(); } ?>
                </li>
                <li>
                  <button id="cartButton" class="btn btn-outline-primary cart-button" 
                        type="button" aria-label="Открыть корзину"
                      data-bs-toggle="modal" data-bs-target="#cartModal">
                    <?php if (empty(session()->get('user.name'))) { ?>
                    <img class="ico" src="<?= base_url(POCKET_STYLE .'/favicon/cart.png'); ?>">
                    <?php } else { echo user_cart(); } ?>
                    <!-- SVG иконка -->
                    <!--svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" 
                      fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1h1a.5.5 0 0 1 .485.379L2.89 5H14.5a.5.5 0 0 1 .49.598l-1.5 6A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.49-.402L1.61 2H.5a.5.5 0 0 1-.5-.5zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm6 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                    </svg-->
                  </button>
                </li>
                <li class="d-none d-md-flex">
                  <button id="theme-toggle" class="theme-btn" aria-label="Переключить тему" 
                        style="margin-right:2.2rem">
                    <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                      viewBox="0 0 24 24" fill="none" stroke="#4d4d4d" stroke-width="1.7"
                      stroke-linecap="round" stroke-linejoin="round" aria-hidden="false">
                      <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                  </button>
                </li>
              </ul>
            </div>
          </div>       

        </nav>
      </header>
      <!---------------- MAIN ---------------->
      <main>
        <div class="container">
          <!-- Модал корзины -->
          <!--div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="cartModalLabel">Ваша корзина</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body"><p>Товары в корзине...</p></div>
                <div class="modal-footer">
                  <a href="/checkout" class="btn btn-primary">Оформить заказ</a>
                  <button type="button" class="btn btn-secondary" 
                        data-bs-dismiss="modal">Продолжить покупки</button>
                </div>
              </div>
            </div>
          </div-->
          <!-- Кнопка для открытия модалки -->

          <!-- Модальное окно корзины -->
          <!--div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="cartModalLabel">Корзина</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                  <!-- Список товаров -->
                  <!--div class="list-group" id="cart-items">
                    <!-- Пример товара -->
                    <!--div class="list-group-item d-flex gap-3 align-items-start" data-item-id="1">
                      <div style="width:120px; flex-shrink:0;">
                        <img src="https://via.placeholder.com/120" alt="Товар" class="img-fluid rounded" />
                      </div>

                      <div class="flex-grow-1 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start">
                          <div>
                            <h6 class="mb-1">Название товара</h6>
                            <small class="text-muted">Краткое описание</small>
                          </div>
                          <div class="text-end">
                            <div class="fw-semibold">Цена: <span class="item-price" data-base-price="199.99">199.99</span> ₽</div>
                          </div>
                        </div>

                        <div class="mt-3 d-flex justify-content-between align-items-center">
                          <div class="input-group" style="width:140px;">
                            <button class="btn btn-outline-secondary btn-decrease" type="button">−</button>
                            <input type="number" class="form-control text-center item-qty" value="1" min="1" />
                            <button class="btn btn-outline-secondary btn-increase" type="button">+</button>
                          </div>

                          <div class="fw-bold">
                            Итог: <span class="item-total">199.99</span> ₽
                          </div>
                        </div>
                      </div>

                      <!-- Кнопки справа: удалить -->
                      <!--div class="ms-3 text-end">
                        <button class="btn btn-sm btn-outline-danger btn-delete" title="Удалить товар" type="button">&times;</button>
                      </div>
                    </div>
                    <!-- /пример товара -->
                  <!--/div>
                </div>

                <div class="modal-footer d-flex flex-column align-items-stretch">
                  <div class="d-flex justify-content-between w-100 mb-2">
                    <div>Товаров: <span id="cart-count">1</span></div>
                    <div class="fw-bold">Итого: <span id="cart-total">199.99</span> ₽</div>
                  </div>

                  <div class="d-flex justify-content-between w-100">
                    <div>
                      <button type="button" class="btn btn-outline-danger" id="clearCartBtn">Очистить корзину</button>
                    </div>
                    <div class="d-flex gap-2">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Продолжить покупки</button>
                      <button type="button" class="btn btn-primary" id="checkoutBtn">Оформить заказ</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div-->

          <!-- Модальное окно корзины -->
          <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="cartModalLabel">Корзина</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>

                <div class="modal-body">
                  <!-- Список товаров -->
                  <div class="list-group" id="cart-items">

                    <div class="list-group-item d-flex flex-column flex-md-row gap-3 align-items-start" data-item-id="1">
                      <div class="item-img" style="width:120px; flex-shrink:0;">
                        <img src="" alt="Товар" class="img-fluid rounded" />
                      </div>

                      <div class="flex-grow-1 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start flex-column flex-md-row">
                          <div>
                            <h6 class="mb-1">Название товара</h6>
                            <small class="text-muted">Краткое описание</small>
                          </div>

                          <div class="text-end mt-2 mt-md-0">
                            <div class="fw-semibold">Цена: <span class="item-price" data-base-price="199.99">199.99</span> ₽</div>
                          </div>
                        </div>

                        <div class="mt-3 d-flex align-items-center gap-2 flex-column flex-sm-row">
                          <div class="d-flex align-items-center w-100 justify-content-between">
                            <!-- Цена за шт. (левая) -->
                            <!--div class="text-muted">
                              <small>Цена за шт.</small>
                            </div-->

                            <!-- Количество (средний блок на десктопе) -->
                            <div class="ms-auto ms-sm-0 me-sm-3 order-2 order-sm-2">
                              <div class="input-group" style="width:140px;">
                                <button class="btn btn-outline-secondary btn-decrease" type="button">−</button>
                                <input type="number" class="form-control text-center item-qty" value="1" min="1" />
                                <button class="btn btn-outline-secondary btn-increase" type="button">+</button>
                              </div>
                            </div>

                            <!-- Итог по товару (правая) -->
                            <div class="fw-bold ms-3 order-3">
                              Итог: <span class="item-total">999.99</span> ₽
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="ms-md-3 mt-3 mt-md-0 text-end">
                        <button class="btn btn-sm btn-outline-danger btn-delete" title="Удалить товар" type="button">&times;</button>
                      </div>
                    </div>

                  </div> <!-- /#cart-items (list-group) -->
                </div> <!-- /.modal-body -->

                <div class="modal-footer d-flex flex-column align-items-stretch">
                  <div class="d-flex justify-content-between w-100 mb-2">
                    <div>Товаров: <span id="cart-count">1</span></div>
                    <div class="fw-bold">Итого: <span id="cart-total">199.99</span> ₽</div>
                  </div>

                  <div class="d-flex justify-content-between w-100">
                    <div>
                      <button type="button" class="btn btn-outline-danger" id="clearCartBtn">Очистить корзину</button>
                    </div>
                    <div class="d-flex gap-2">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Продолжить покупки</button>
                      <button type="button" class="btn btn-primary" id="checkoutBtn">Оформить заказ</button>
                    </div>
                  </div>
                </div> <!-- /.modal-footer -->

              </div> <!-- /.modal-content -->
            </div> <!-- /.modal-dialog -->
          </div> <!-- /.modal -->

          <?= $view_file; ?>

          <button id="top_btn" title="Перейти к началу"> 
            <i class="fa-solid fa-chevron-up"></i>
          </button>
          <form id="search" class="search-form" action="">
            <input id="input" class="form-control form-control-sm hide"
                  type="search" placeholder="&nbsp; и с к а т ь" aria-label="поиск">
            <button id="submit" class="btn btn-sm" type="submit">
              <i class="fa-solid fa-magnifying-glass"></i>
            </button>
          </form>
          <div class="toast-container position-fixed bottom-0 start-0" 
              style="z-index: 9999; margin:2.4rem 4.4rem">
          </div>
          <!-- Шаблон тоста (чертеж для JS) -->
          <template id="toast-template">
            <div class="toast custom-toast border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
              <div class="d-flex p-3 align-items-center">
                <div class="toast-body d-flex align-items-center w-100">
                  <!-- Контейнер для штампа -->
                  <div class="jp-stamp-wrapper">
                    <span class="jp-icon"></span>
                  </div>
                  <!-- Контейнер для текста -->
                  <div class="ms-4">
                    <span class="d-block fw-bold jp-status-label"></span>
                    <span class="d-block small opacity-75 jp-detail-text"></span>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </div>
      </main>
      <!---------------- FOOTER ---------------->
      <footer>
        <section class="call-back">
          <div class="col-md-8 offset-md-2 call-back">
            <h6>Если у вас возникли вопросы, пожалуйста, оставьте свой номер телефона, и мы<br>
                обязательно Вам перезвоним.</h6>          
            <form>
              <div class="row">
                <div class="col-lg-4">
                  <input type="text" class="form-control" placeholder="имя">
                </div>
                <div class="col-lg-4">
                  <input type="text" class="form-control" id="phone" placeholder="телефон">
                </div>
                <div class="col-lg-4">
                  <button class="btn btn-primary" type="submit">отправить</button>
                </div>
              </div>
            </form>        
          </div><!--col-md-8 offset-md-2-->
        </section><!--call-back-->
        <section class="info">
          <div class="container col-md-8 offset-md-2">
            <div class="row">
              <div class="col-md-4 info">
                <h6>связаться с нами</h6>
                <div class="contact">
                  <ul>
                    <li><a href="tel:+79124174818">+7(912)4174818</a></li>
                    <!--li><a href="mailto:test@email.com">Japan-in.Ru@mail</a></li-->
                    <li><a href="https://wa.me/79124174818" target="_blank">WhatsApp</a></li>
                    <li><a href="https://t.me/satomi_jap" target="_blank">Telegram</a></li>
                  </ul>
                </div>
              </div><!--+-->
              <div class="col-md-4 info">
                <h6>наш адрес</h6>
                <div class="address">
                  <a href="https://www.google.com/maps/search/?api=1&query=45.011728,39.123093"
                      target="_blank">
                  <!--a href="https://yandex.ru/maps/?pt=39.123093,45.011728&z=15&
                        l=map&sll=39.123093,45.011728" target="_blank"-->
                    <p>Краснодар, Крылатая 2<br>
                       Пн-Пт:&nbsp;10:00-18:00<br><!--&nbsp; (от «non-breaking space»)-->
                       Сб:&nbsp;12:00-18:00<!--&emsp;(широкий пробел)-->
                    </p>
                  </a>
                </div>
              </div><!--+-->
              <div class="col-md-4 info">
                <h6>оплата и доставка</h6>
                <div class="payment_delivery">
                  <ul>
                    <li><a href="/product/delivery'">Условия доставки</a></li>
                    <li><a href="/product/delivery">Безопасность оплаты</a></li>
                    <li><a href="/product/delivery">Возврат товара</a></li>
                  </ul>
                </div>
              </div>
            <div><!--row-->
          </div>
        </section><!--info-->
        <section class="copyright">
          <div class="copyright">
            <a  href="<?= base_url('/'); ?>">
              <img class="brand_footer" src="<?= base_url(POCKET_STYLE .'/favicon/home.png'); ?>">
            </a>
            <!--p>&copy; ~ 2.3</p-->
          </div>
        </section>
        <span id="about_us"></span>
      </footer>
    </div><!--wrapper-->
    <script>
      // Проверяем наличие переменной в глобальном объекте `window`
      if (typeof window.baseUrl === 'undefined') {
        // Объявляем константу только после проверки
        const baseUrl = '<?= base_url('/'); ?>';
        // Делаем её доступной глобально
        window.baseUrl = baseUrl;
      }
    </script>   
    <script src="<?= base_url('/library/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?= base_url('/library/js/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('/library/js/jquery-ui.min.js'); ?>"></script>
    <script src="<?= base_url('/library/owlcarousel/owl.carousel.min.js'); ?>"></script>
    <script src="<?= base_url('/library/js/jquery.spincrement.min.js'); ?>"></script>
    <script src="<?= base_url('/library/js/jquery.maskedinput.min.js'); ?>"></script>
    <script type="module" src="<?= base_url('/js/main.js'); ?>"></script>
  </body>
</html>
