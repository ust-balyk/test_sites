<!DOCTYPE html>
<html lang="ru" class="notranslate">
  <head>
    <base href="<?= base_url('/'); ?>">
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= hsc(session()->get('csrf_token')) ?>">
    <meta name="referrer" content="no-referrer-when-downgrade">
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Японские витамины - покупайте по доступной цене на Japan-in.Ru!">
    <meta name="keywords" content="japan-in.ru, satomi-japan.com, японские витамины, японская косметика,
      витамины из японии, витамины и минералы, японские витамины в наличии, витамины для глаз, антиоксиданты,
      коллаген, сквален, наттокиназа, пробиотики, средства для суставов, высшее качетво добавок,
      японские витамины для мужского здоровья, витамины для женского здоровья, витамины для детей">
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="<?= base_url(POCKET_STYLE.'/favicon/icon.png'); ?>" type="image/png">
    <link rel="stylesheet" href="<?= base_url('/library/fontawesome/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('/library/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('/library/js/jquery-ui.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('/library/owlcarousel/owl.carousel.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('/library/owlcarousel/owl.theme.default.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url(POCKET_STYLE.'/css/main.css'); ?>">
    <link rel="stylesheet" href="<?= base_url(POCKET_STYLE.'/css/media.css'); ?>">
    <meta property="og:type" content="product">
    <meta property="og:title" content="<?= hsc($productTitle) ?>">
    <meta property="og:description" content="<?= hsc($shortDescription) ?>">
    <meta property="og:url" content="<?= hsc($fullUrl) ?>">
    <meta property="og:image" content="<?= hsc($imageUrl) ?>">
    <meta property="product:price:amount" content="<?= hsc($numericPrice) ?>">
    <meta property="product:price:currency" content="RUB">
    <?php if (!empty($schemaData)): ?>
      <script type="application/ld+json">
        <?= json_encode($schemaData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
      </script>
    <?php endif; ?>
  </head>
  <body>
    <div style="height:1px;border-bottom:1px dotted #744474;clear:both"></div>
    <?= db()->user_back(); ?>
    <div class="wrapper">
      <!---------------- HEADER ---------------->
      <header>
        <nav class="navbar navbar-expand-lg navbar-light fixed-top nav-shadow">
          <a class="navbar-brand" href="<?= base_url('/home'); ?>">
            <img class="brand" src="<?= base_url(POCKET_STYLE .'/favicon/home.png'); ?>">
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
              aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item dropdown has-megamenu">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown"
                  role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Каталог
                </a>

                <?php new App\Widgets\Menu\Menu(); ?>

              </li> 
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
            </ul>
            <ul class="navbar-nav d-flex align-items-center gap-3 mb-2 mb-lg-0">
              <li class="nav-item dropdown user">
                <?php if (empty(session()->get('user.name'))) { ?>
                <a href="/login">
                  <img class="ico" id="user" src="<?= base_url(POCKET_STYLE .'/favicon/user_add.png'); ?>"/>
                </a>
                <?php } else { echo user_icon(); } ?>
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
            </ul>
          </div>
        </nav>
      <!---------------- MAIN ---------------->
      <main>
        <div class="container">
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
        <section class="info">
          <div class="col-md-8 offset-md-2">
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
                  <!--a href="https://www.google.com/maps/search/?api=1&query=45.011728,39.123093"
                      target="_blank"-->
                  <a href="https://yandex.ru/maps/?pt=39.123093,45.011728&z=15&
                        l=map&sll=39.123093,45.011728" target="_blank">
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
                    <li><a href="/product/delivery">Условия доставки</a></li>
                    <li><a href="/product/delivery">Безопасность оплаты</a></li>
                    <li><a href="/product/delivery">Возврат товара</a></li>
                  </ul>
                </div>
              </div>
            </div><!--row-->
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
      if (typeof window.baseUrl === 'undefined') { 
        const baseUrl = '<?= base_url('/'); ?>';
        window.baseUrl = baseUrl;
      }
    </script>   
    <script src="<?= base_url('/library/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?= base_url('/library/js/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('/library/js/jquery-ui.min.js'); ?>"></script>
    <script src="<?= base_url('/library/owlcarousel/owl.carousel.min.js'); ?>"></script>
    <script src="<?= base_url('/library/js/jquery.spincrement.min.js'); ?>"></script>
    <script src="<?= base_url('/library/js/jquery.maskedinput.min.js'); ?>"></script>
    <script type="module" src="<?= base_url(POCKET_STYLE .'/js/main.js'); ?>"></script>
  </body>
</html>
