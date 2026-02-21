<?=db()->user_back();?>
  <section class="product">
    <div class="container product">

      <div class="container breadcrumb">
        <nav class="breadcrumb" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="<?= base_url('/'); ?>">
                <img class="link_to_home" src="<?= base_url(POCKET_STYLE.'/favicon/home.svg'); ?>"
                    alt="link_to_the_home_page">
                </img>
              </a>
            </li>
            <li class="breadcrumb-item">
              <a href="<?= base_url('/category'); ?>">
                Категория
              </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Продукт</li>
          </ol>
        </nav>
      </div>

      <h1 class="text-center title_category">Продукт</h1>

      <div class="row product">

        <div class="col-md-6 image" style="">
          <a id="" href="#" class="">
            <img src="<?= base_url('/images/for-face/11642.jpg'); ?>" alt="">
          </a>
        </div>

        <div class="col-md-6 content">
          <div class="container content">
            <div class="product-card-details">
              <h6 class="product-card-title">
                <a href="#">
                  Эссенция против старения кожи с астаксантином Astaxanthin Aging Care Essence Re'senza
                </a>
              </h6>
            <div class="product-card-price">
              33 000р<del>35 000р</del>
            </div>
            <div class="product-card-btns">
              <a href="#" class="btn btn btn-outline-secondary add-to-favorites">
                <i class="fa-solid fa-heart"></i>
              </a>
              <a href="#" class="btn btn-outline-secondary add-to-cart">
                <i class="fa-solid fa-cart-shopping"></i>
              </a>
            </div>
          </div><!--container-->
        </div><!--col-6-->
      </div><!--row product-->
    </div><!--container product-->
  </section>
  <section class="delivery">
    <div class="container delivery">
      <div class="block">
        <h6 style="color: #4295e4; margin-top: 20px">ДОСТАВКА</h6>
        <p>
        Мы сотрудничаем с логистической компанией «Служба Доставки Экспресс-Курьер».<br> 
        <strong>
        Стоимость и сроки доставки рассчитываются автоматически и соответствуют тарифам перевозчика.
        </strong><br>
          <ul>
            <li>
            <strong>Сроки отгрузки:</strong> 
            Отправка заказа осуществляется в течение 3 рабочих дней после оформления.
            </li>
            <li>
            <strong>Отслеживание:</strong>
            После передачи заказа в службу доставки вы получите трек-номер 
            для отслеживания посылки на указанный при регистрации e-mail или в мессенджер.
            </li>
            <li>
            <strong>Важно:</strong> 
            При заказе за пределы России или не входящие в географию присутствия «СДЭК» регионы,
            пожалуйста, свяжитесь с нами в WhatsApp для согласования способа доставки.
            </li>
          </ul>
        </p>
        <p style="margin-bottom: 40px">
      </div>

      <div class="block" style="margin-top: 10px">
        <h6 style="color: #4295e4; margin-top: 20px">ОПЛАТА</h6>
        <p><strong>
        Платежи по банковским картам проводятся в строгом соответствии с требованиями платежных систем.
        </strong><br>
        При оплате на сайте вы будете перенаправлены на защищённый платежный шлюз АО «Тинькофф Банк». 
        Оплата происходит через зашифрованный протокол SSL 
        <strong>
        без комиссии картой любого банка.
        </strong>
        <p style="color: orange; margin-bottom: 40px">
        Мы не получаем и не сохраняем данные вашей карты, равно как и не несём ответственности 
        за несоблюдение сроков доставки по вине перевозчика.
        </p>
      </div>

      <div class="block" style="margin-top: 10px">
        <h6 style="color: #4295e4; margin-top: 20px">ВОЗВРАТ</h6>
        <p style="margin-bottom: 40px">
        Если какие либо товары приобретённые в нашем магазине вызвали аллергию, 
        просрочены или не соответствуют описанию — 
        <strong>
        возврат возможен в любое время в пределах срока годности 
        при условии что товар не использовался, сохранён товарный вид и упаковка.  
        </strong></p>
      </div>
    </div>
  </section>
