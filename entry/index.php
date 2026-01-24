<?php defined('PROTECTED_ACCESS') && PROTECTED_ACCESS === $entry; ?>
<!DOCTYPE html>
<html lang="ru" class="notranslate">
  <head>
    <meta charset="UTF-8">
    <title>Здоровье из Японии</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="<?= base_url(POCKET_STYLE.'/favicon/icon.png'); ?>" type="image/png">
    <link rel="preload" href="<?= base_url('/library/fontawesome/css/all.min.css'); ?>" as="style">
    <link rel="stylesheet" href="<?= base_url('/library/fontawesome/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('/library/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url(POCKET_STYLE.'/css/main.css'); ?>">
    <link rel="stylesheet" href="<?= base_url(POCKET_STYLE.'/css/media.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('/library/owlcarousel/owl.carousel.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('/library/owlcarousel/owl.theme.default.min.css'); ?>">
  </head>
  <body>
    <div style="height:1px;background:#a9ddf9;border-bottom:1px dotted #744474;clear:both"></div>
    <div class="wrapper">
      <!---------------- HEADER ---------------->
      <header>
        <nav class="navbar navbar-expand-lg navbar-light fixed-top nav-shadow">
          <div class="container">
            <a class="navbar-brand" href="<?= base_url('/'); ?>">
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
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Каталог
                  </a>
                  <?php
                    new App\Widgets\Menu\Menu([
                      'container' => 'div',
                      'class'     => 'container dropdown-menu megamenu',
                      'prepend'   => '<div class="row g-3">',
                      'append'    => '</div>',
                      'attrs'     => ['role' => 'menu',],
                    ]);
                  ?>
                </li>
                <li class="nav-item">
                  <div class="hide">
                    <a class="nav-link" href="<?= base_url('/page'); ?>">О нас</a>
                  </div>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="tel:+79124174818">+7(912)4174818</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?= base_url('/data'); ?>">Условия доставки</a>
                </li>
              </ul>
            </div><!--navbar-collapse-->
            <div class="navbar-icon">
              <ul>
                <!--li>
                  <a href="#">
                    <img class="ico"
                        src="<?= base_url(POCKET_STYLE .'/favicon/heart.png'); ?>">
                  </a>
                </li>
                <li>
                  <a href="#">
                    <img class="ico"
                        src="<?= base_url(POCKET_STYLE .'/favicon/cart.png'); ?>">
                  </a>
                </li>
                <li-->
                  <a href="<?= base_url('/logout'); ?>">выход
                    <!--img class="ico" id="user" src="<?= base_url(POCKET_STYLE .'/favicon/user_out.png');?>"/-->
                  </a>
                </li>
              </ul>
            </div><!--navbar-icon-->
          </div><!--container-->
        </nav>
        <!--div class="banner">
          <img class="banner-img" src="<?= base_url(POCKET_STYLE .'/assets/banner/banner.jpg'); ?>"
              alt="изображение горы Фудзияма">
          <!--img src="<?//= base_url(POCKET_STYLE .'/assets/banner/banner.svg'); ?>"
            alt="изображение горы Фудзияма"-->
        <!--/div><!--banner-->
      </header>
  
      <main>
        <div class="container">
          <?= $view_file; ?>
          <form id="search" class="search-form" action="">
            <input id="input" class="form-control form-control-sm hide"
                  type="search" placeholder="&nbsp; и с к а т ь" aria-label="поиск">
            <button id="submit" class="btn btn-sm" type="submit">
              <i class="fa-solid fa-magnifying-glass"></i>
            </button>
          </form>
        </div>
      </main>

    </div><!--wrapper-->
    <script src="<?= base_url('/library/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?= base_url('/library/js/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('/library/owlcarousel/owl.carousel.min.js'); ?>"></script>
    <script src="<?= base_url('/library/js/jquery.spincrement.min.js'); ?>"></script>
    <script src="<?= base_url('/library/js/jquery.maskedinput.min.js'); ?>"></script>
    <script src="<?= base_url(POCKET_STYLE .'/js/main.js'); ?>"></script>
  </body>
</html>
