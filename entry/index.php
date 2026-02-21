<?php 
if(defined('PROTECTED_ACCESS') && PROTECTED_ACCESS === $entry) {
  
  $icon         = base_url(POCKET_STYLE.'/favicon/icon.png');
  $font         = base_url('/library/fontawesome/css/all.min.css');
  $font_2       = base_url('/library/fontawesome/css/all.min.css');
  $bootstrap    = base_url('/library/bootstrap/css/bootstrap.min.css');
  $bootstrap_js = base_url('/library/bootstrap/js/bootstrap.bundle.min.js');
  $jquery       = base_url('/library/js/jquery.min.js');
  $jquery_2     = base_url('/library/js/jquery.spincrement.min.js');
  $jquery_3     = base_url('/library/js/jquery.maskedinput.min.js');
  $js           = base_url(POCKET_STYLE .'/js/main.js');
  $main_css     = base_url(POCKET_STYLE.'/css/main.css');
  $media_css    = base_url(POCKET_STYLE.'/css/media.css');
  $home         = base_url('/');
  $home_brand   = base_url(POCKET_STYLE .'/favicon/home.png');
  $out          = base_url('/logout');

  echo <<<part_one

<!DOCTYPE html>
<html lang="ru" class="notranslate">
  <head>
    <meta charset="UTF-8">
    <title>Панель управления</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="$icon" type="image/png">
    <link rel="preload" href="$font" as="style">
    <link rel="stylesheet" href="$font_2">
    <link rel="stylesheet" href="$bootstrap">
    <link rel="stylesheet" href="$main_css">
    <link rel="stylesheet" href="$media_css">
  </head>
  <body>
    <div style="height:1px;background:#a9ddf9;border-bottom:1px dotted #744474;clear:both"></div>
    <div class="wrapper">
      <!---------------- HEADER ---------------->
      <header>
        <nav class="navbar navbar-expand-lg navbar-light fixed-top nav-shadow">
          <div class="container">
            <a class="navbar-brand" href="$home">
              <img class="brand" src="$home_brand">
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

part_one;

                   new App\Widgets\Menu\Menu([
                     'container' => 'div',
                     'class'     => 'container dropdown-menu megamenu',
                     'prepend'   => '<div class="row g-3">',
                     'append'    => '</div>',
                     'attrs'     => ['role' => 'menu',],
                   ]);
  
  echo <<<part_two

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
                  <a href="$out">
                    выход
                  </a>
                </li>
              </ul>
            </div><!--navbar-icon-->
          </div><!--container-->
        </nav>
      </header>
  
      <main>
        <div class="container"></div>
      </main>

    </div><!--wrapper-->
    <script src="$bootstrap_js"></script>
    <script src="$jquery"></script>
    <script src="$jquery_2"></script>
    <script src="$jquery_3"></script>
    <script src="$js"></script>
  </body>
</html>

part_two;

} else {

  app()->response->redirect("/");
  /*
  echo  "<div style=\"text-align:center;background-color:red\"><br>
          <h2>&emsp;Доступ запрещен.</h2><br>
         </div>";
  */

}
