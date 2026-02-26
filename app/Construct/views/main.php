<?php 
//dump($_COOKIE);
//dump($_SESSION);
?>
  <section class="superiority">
    <div class="container superiority">
      <div class="row">
        <p class="header_post"><strong>В нашем магазине вы найдете тщательно отобранные продукты от проверенных 
        японских производителей. Мы гарантируем оригинальность товаров и предлагаем подробные консультации 
        по выбору средств, идеально подходящих именно вам.<br>
        Желаем полезных и выгодных приобретений!</strong></p>
        <h1>ВЫБЕРАЙТЕ&nbsp;&nbsp;JAPAN-IN.RU!</h1>

        <div class="col-md-4 g-md-4">
          <div class="high_quality">
            <div class="high_quality_top">
              <img class="high_quality" src="<?= base_url(POCKET_STYLE .'/assets/quality/quality.png'); ?>"
                alt="Оригинальный товар!">
              <h2 style="font-size:18px;">Оригинальный товар!</h2>
            </div>
          </div>
        </div>
        <div class="col-md-4 g-md-4">
          <div class="high_quality">
            <div class="high_quality_top">
              <img class="high_quality" src="<?= base_url(POCKET_STYLE .'/assets/quality/quality.png'); ?>"
                alt="Лучший срок годности!">
              <h2 style="font-size:18px;">Лучший срок годности!</h2>
            </div>
          </div> 
        </div>
        <div class="col-md-4 g-md-4">
          <div class="high_quality">
            <div class="high_quality_top">
              <img class="high_quality" src="<?= base_url(POCKET_STYLE .'/assets/quality/quality.png'); ?>"
                alt="Качественная логистика!">
              <h2 style="font-size:18px;">Качественная логистика!</h2>
            </div>
          </div> 
        </div>
      
      </div><!--row-->
    </div><!--container-->
  </section><!--superiority-->

  <section class="achievements"><!--достижения-->
    <div class="border border-dark-subtle">
      <div class="row gy-3">

        <div class="col-md-3 col-6 counter">
          <h4 class="counter-num">3,267</h4>
          <span>счастливых клиентов</span>
        </div>

        <div class="col-md-3 col-6 counter">
          <h4 class="counter-num">1,195</h4>
          <span>продуктов на выбор</span>
        </div>

        <div class="col-md-3 col-6 counter">
          <h4 class="counter-num">378</h4>
          <span>продаж в день</span>
        </div>
        
        <div class="col-md-3 col-6 counter">
          <h4 class="counter-num">18</h4>
          <span>лет на рынке</span>
        </div>

      </div><!--row-->
    </div><!--container-->
  </section><!--achievements-->

  <!--section class="carouse-promo">
    <div class="container promo">
      <div class="slider-header">
        <a href="#" class="btn btn-sm btn-outline-secondary promo">
          <h5>товары по акции</h5>
        </a>
        <div class="slider-btn-control">
          <span class="prev-btn"><i class="fa-solid fa-chevron-left"></i></span>
          <span class="next-btn"><i class="fa-solid fa-chevron-right"></i></span>
        </div>
      </div>
      
      <div class="owl-carousel owl-theme" id="slider-promo">

        <div class="product-card" itemscope itemtype="https://schema.org/Product">
          <a href="#">
            <div class="product-card-img">
              <img src="<?= base_url('/images/for-face/11642.jpg'); ?>"
                loading="lazy" alt="изображение продукта" itemprop="image">
            </div>
          </a>
          <div class="product-card-details">
            <h6 class="product-card-title" itemprop="name">
              <a href="#">
                Эссенция против старения кожи с астаксантином Astaxanthin Aging Care Essence Re'senza
              </a>
            </h6>
            <div class="product-card-price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                <p style="margin:0;padding:0;" itemprop="price">33000р<del>35000р</del></p>
            </div>
            <div class="product-card-btns">
              <a href="#" class="btn btn btn-outline-secondary add-to-favorites">
                <i class="fa-solid fa-heart"></i>
              </a>
              <a href="#" class="btn btn-outline-secondary add-to-cart">
                <i class="fa-solid fa-cart-shopping"></i>
              </a>
            </div>
          </div>
        </div><!--product-card-->

        <!--div class="product-card">
          <a href="#">
            <div class="product-card-img">
              <img src="<?= base_url('/images/for-face/11642.jpg'); ?>" alt="">
            </div>
          </a>
          <div class="product-card-details">
            <h6 class="product-card-title">
              <a href="#">
                Эссенция против старения кожи с астаксантином Astaxanthin Aging Care Essence Re'senza
                Эссенция против старения кожи с астаксантином Astaxanthin Aging Care Essence Re'senza
                Эссенция против старения кожи с астаксантином Astaxanthin Aging Care Essence Re'senza
              </a>
            </h6>
            <div class="product-card-price">
              33000р<del>35000р</del>
            </div>
            <div class="product-card-btns">
              <a href="#" class="btn btn btn-outline-secondary add-to-favorites" title="добавить в избранное">
                <i class="fa-solid fa-heart"></i>
              </a>
              <a href="#" class="btn btn-outline-secondary add-to-cart" title="добавить в корзину">
                <i class="fa-solid fa-cart-shopping"></i>
              </a>
            </div>
          </div>
        </div><!--product-card-->

        <!--div class="product-card">
          <a href="#">
            <div class="product-card-img">
              <img src="<?= base_url('/images/aromatherapy/11383.png'); ?>" alt="">
            </div>
          </a>
          <div class="product-card-details">
            <h6 class="product-card-title">
              <a href="#">Премиум арома-стик для дома</a>
            </h6>
            <div class="product-card-price">
              33000р<del>35000р</del>
            </div>
            <div class="product-card-btns">
              <a href="#" class="btn btn-outline-secondary add-to-favorites">
                <i class="fa-solid fa-heart"></i>
              </a>
              <a href="#" class="btn btn-outline-secondary add-to-cart">
                <i class="fa-solid fa-cart-shopping"></i>
              </a>
            </div>
          </div>
        </div><!--product-card-->

        <!--div class="product-card">
          <a href="#">
            <div class="product-card-img">
              <img src="<?= base_url('/images/for-body/11870.jpeg'); ?>" alt="">
            </div>
          </a>
          <div class="product-card-details">
            <h6 class="product-card-title">
              <a href="#">товар со скидкой!товар со скидкой!товар со скидкой!товар со скидкой!</a>
            </h6>
            <div class="product-card-price">
              33000р<del>35000р</del>
            </div>
            <div class="product-card-btns">
              <a href="#" class="btn btn-outline-secondary add-to-favorites">
                <i class="fa-solid fa-heart"></i>
              </a>
              <a href="#" class="btn btn-outline-secondary add-to-cart">
                <i class="fa-solid fa-cart-shopping"></i>
              </a>
            </div>
          </div>
        </div><!--product-card-->

        <!--div class="product-card">
          <a href="#">
            <div class="product-card-img">
              <img src="<?= base_url('/images/sets-gift/11856.jpeg'); ?>" alt="">
            </div>
          </a>
          <div class="product-card-details">
            <h6 class="product-card-title">
              <a href="#">
                Антицеллюлитное масло для тела BIJOU DE MER ROYALSPA Golden Body Oil с эффектом сияния (190 мл)
              </a>
            </h6>
            <div class="product-card-price">
              33000р<del>35000р</del>
            </div>
            <div class="product-card-btns">
              <a href="#" class="btn btn-outline-secondary add-to-favorites">
                <i class="fa-solid fa-heart"></i>
              </a>
              <a href="#" class="btn btn-outline-secondary add-to-cart">
                <i class="fa-solid fa-cart-shopping"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section-->

  <section class="categories">
    <div class="container categories">
    
    <h2 class="text-center categories-title">Категории</h2>
    
    <noindex><a ref="nofollow" href="https://ru.wikipedia.org/wiki/%D0%A1%D1%83%D0%B4%D0%B7%D1%83%D0%BA%D0%B8_%D0%A5%D0%B0%D1%80%D1%83%D0%BD%D0%BE%D0%B1%D1%83" target="_blank">
      <p class="mb-0">Хризантема в японской культуре является символом долголетия, счастья и мудрости.</p>
    </a></noindex>
    <div class="row">

      <div class="col-md-4 category" >
        <div class="image-container" itemscope itemtype="https://schema.org/Product">
          <a href="<?= base_url('/category'); ?>">
            <picture>
              <source media="(min-width: 768px)" 
                    srcset="<?= base_url(POCKET_STYLE .'/assets/categories/1.jpg'); ?>" alt="категория продукта - в наличии">
                <div class="category_name">
                  <h5>в наличии</h5>
                </div>
              <img src="<?= base_url(POCKET_STYLE .'/assets/categories/white.jpg'); ?>" alt="">
            </picture>
          </a>
        </div>
      </div>

      <div class="col-md-4 category" itemscope itemtype="https://schema.org/Product">
        <div class="image-container">
          <a href="#">
            <picture>
              <source media="(min-width: 768px)"
                    srcset="<?= base_url(POCKET_STYLE .'/assets/categories/2.jpg'); ?>" alt="категория продукта - для женщин">
                <div class="category_name">
                  <h5>для женщин</h5>
                </div>
                <img src="<?= base_url(POCKET_STYLE .'/assets/categories/grey.jpg'); ?>" alt="">
            </picture>
          </a>
        </div>
      </div>

      <div class="col-md-4 category" itemscope itemtype="https://schema.org/Product">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/3.jpg'); ?>" alt="категория продукта - для детей">
              <div class="category_name"><h5>для детей</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-4 category" itemscope itemtype="https://schema.org/Product">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/4.jpg'); ?>" alt="категория продукта - для мужчин">
              <div class="category_name"><h5>для мужчин</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-4 category" itemscope itemtype="https://schema.org/Product">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/5.jpg'); ?>" alt="категория продукта - для тела">
              <div class="category_name"><h5>для тела</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-4 category" itemscope itemtype="https://schema.org/Product">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/6.jpg'); ?>" alt="категория продукта - для лица">
              <div class="category_name"><h5>для лица</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-4 category" itemscope itemtype="https://schema.org/Product">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/7.jpg'); ?>" alt="категория продукта - для полости рта">
              <div class="category_name"><h5>для полости рта</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-4 category" itemscope itemtype="https://schema.org/Product">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/8.jpg'); ?>" alt="категория продукта - для волос">
              <div class="category_name"><h5>для волос</h5></div>
          </a>
        </div>
      </div>
  
      <div class="col-md-4 category" itemscope itemtype="https://schema.org/Product">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/9.jpg'); ?>" alt="категория продукта - для ног">
              <div class="category_name"><h5>для рук</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-4 category" itemscope itemtype="https://schema.org/Product">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/10.jpg'); ?>" alt="категория продукта - для ног">
              <div class="category_name"><h5>для ног</h5></div>
          </a>
        </div>
      </div>
 
      <div class="col-md-4 category" itemscope itemtype="https://schema.org/Product">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/11.jpg'); ?>" alt="категория продукта - декоративная косметика">
              <div class="category_name"><h5>декоративная косметика</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-4 category" itemscope itemtype="https://schema.org/Product">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/12.jpg'); ?>" alt="категория продукта - подарочные наборы">
              <div class="category_name"><h5>подарочные наборы</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-4 category" itemscope itemtype="https://schema.org/Product">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/13.jpg'); ?>" alt="категория продукта - приборы и массажёры">
              <div class="category_name"><h5>приборы и массажёры</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-4 category" itemscope itemtype="https://schema.org/Product">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/14.jpg'); ?>" alt="категория продукта - товары для дома">
              <div class="category_name"><h5>товары для дома</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-4 category" itemscope itemtype="https://schema.org/Product">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/15.jpg'); ?>" alt="категория продукта - продукты питания">
              <div class="category_name"><h5>продукты питания</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-4 category" itemscope itemtype="https://schema.org/Product">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/16.jpg'); ?>" alt="категория продукта - ароматерапия">
              <div class="category_name"><h5>ароматерапия</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-4 category" itemscope itemtype="https://schema.org/Product">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/17.jpg'); ?>" alt="категория продукта - аксессуары">
              <div class="category_name"><h5>аксессуары</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-4 category" itemscope itemtype="https://schema.org/Product">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/18.jpg'); ?>" alt="категория продукта - зоотовары">
              <div class="category_name"><h5>зоотовары</h5></div>
          </a>
        </div>
      </div>

    </div><!--row-->
      
    <div class="writer">
      <noindex><a ref="nofollow" href="https://ru.wikipedia.org/wiki/%D0%A1%D1%83%D0%B4%D0%B7%D1%83%D0%BA%D0%B8_%D0%A5%D0%B0%D1%80%D1%83%D0%BD%D0%BE%D0%B1%D1%83" target="_blank">
        <h5>鈴木春信</h5>
      </a></noindex>
 
    </div>
    </div>
  </section><!--categories-->

  <section class="carousel-promo popular">
    <div class="container popular">
      <div class="slider-header">
        <a href="#" class="btn btn-sm btn-outline-secondary popular">
          <h5>популярные товары</h5>
        </a>
        <div class="slider-btn-control">
          <span class="prev-btn"><i class="fa-solid fa-chevron-left"></i></span>
          <span class="next-btn"><i class="fa-solid fa-chevron-right"></i></span>
        </div>
      </div><!--slider-header-->

      <div class="owl-carousel owl-theme" id="slider-popular">

        <div class="product-card" itemscope itemtype="https://schema.org/Product"><!--popular-card-->
          <a href="#">
            <div class="product-card-img">
              <img src="<?= base_url('/images/for-face/11642.jpg'); ?>"
                loading="lazy" alt="изображение продукта" itemprop="image">
            </div>
          </a>
          <div class="product-card-details">
            <h6 class="product-card-title" itemprop="name">
              <a href="#">
                Эссенция против старения кожи с астаксантином Astaxanthin Aging Care Essence Re'senza
              </a>
            </h6>
            <div class="product-card-price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                <p style="margin:0;padding:0;" itemprop="price">33000р<del>35000р</del></p>
            </div>
            <div class="product-card-btns">
              <a href="#" class="btn btn btn-outline-secondary add-to-favorites">
                <i class="fa-solid fa-heart"></i>
              </a>
              <a href="#" class="btn btn-outline-secondary add-to-cart">
                <i class="fa-solid fa-cart-shopping"></i>
              </a>
            </div>
          </div>
        </div><!--popular-card-->

        <div class="product-card"><!--popular-card-->
          <a href="#">
            <div class="product-card-img">
              <img src="<?= base_url('/images/for-face/11642.jpg'); ?>" loading="lazy" alt="">
            </div>
          </a>
          <div class="product-card-details">
            <h6 class="product-card-title">
              <a href="#">
                Эссенция против старения кожи с астаксантином Astaxanthin Aging Care Essence Re'senza
              </a>
            </h6>
            <div class="product-card-price">
              33000р<del>35000р</del>
            </div>
            <div class="product-card-btns">
              <a href="#" class="btn btn btn-outline-secondary add-to-favorites">
                <i class="fa-solid fa-heart"></i>
              </a>
              <a href="#" class="btn btn-outline-secondary add-to-cart">
                <i class="fa-solid fa-cart-shopping"></i>
              </a>
            </div>
          </div>
        </div><!--popular-card-->

        <div class="product-card"><!--favorite-card-->
          <a href="#">
            <div class="product-card-img">
              <img src="<?= base_url('/images/for-face/11642.jpg'); ?>" loading="lazy" alt="">
            </div>
          </a>
          <div class="product-card-details">
            <h6 class="product-card-title">
              <a href="#">
                Эссенция против старения кожи с астаксантином Astaxanthin Aging Care Essence Re'senza
              </a>
            </h6>
            <div class="product-card-price">
              33000р<del>35000р</del>
            </div>
            <div class="product-card-btns">
              <a href="#" class="btn btn btn-outline-secondary add-to-favorites">
                <i class="fa-solid fa-heart"></i>
              </a>
              <a href="#" class="btn btn-outline-secondary add-to-cart">
                <i class="fa-solid fa-cart-shopping"></i>
              </a>
            </div>
          </div>
        </div><!--product-card-->

        <div class="product-card"><!--favorite-card-->
          <a href="#">
            <div class="product-card-img">
              <img src="<?= base_url('/images/for-face/11642.jpg'); ?>" loading="lazy" alt="">
            </div>
          </a>
          <div class="product-card-details">
            <h6 class="product-card-title">
              <a href="#">
                Эссенция против старения кожи с астаксантином Astaxanthin Aging Care Essence Re'senza
              </a>
            </h6>
            <div class="product-card-price">
              33000р<del>35000р</del>
            </div>
            <div class="product-card-btns">
              <a href="#" class="btn btn btn-outline-secondary add-to-favorites">
                <i class="fa-solid fa-heart"></i>
              </a>
              <a href="#" class="btn btn-outline-secondary add-to-cart">
                <i class="fa-solid fa-cart-shopping"></i>
              </a>
            </div>
          </div>
        </div><!--product-card-->
      
      </div><!--slider-popular-->
    </div><!--container-->
  </section><!--favorite-promo-->
  <section class="footer_post">
    <div class="container d-none d-md-block">
      <p><strong>Японская философия красоты основана на бережном отношении к коже 
        и профилактике возрастных изменений. Косметика из Японии отличается:</strong></p>
      <ol>
	    <li><strong>Натуральными формулами с высокой концентрацией активных компонентов</strong></li>
	    <li><strong>Инновационными технологиями производства и упаковки</strong></li>
	    <li><strong>Деликатным воздействием даже на чувствительную кожу</strong></li>
	    <li><strong>Доказанной эффективностью и строгим контролем качества</strong></li>
      </ol>
      <p><strong>Витаминные комплексы из Японии созданы с учетом потребностей современного человека. 
        Они помогают поддерживать иммунитет, улучшают состояние кожи, волос и ногтей, 
        повышают внутреннюю энергию и общий тонус организма, даруют позитивные перспективы.</strong></p>
      <p><strong>В нашем магазине вы найдете тщательно отобранные продукты от проверенных 
        японских производителей. 
        Мы гарантируем оригинальность товаров и предлагаем подробные консультации по выбору средств, 
        идеально подходящих именно вам. Желаем полезных и выгодных приобретений!</strong></p>
      <h3 style="font-size:22px;text-align:center;">
        Откройте для себя секреты японской красоты и долголетия!</h3>
    </div>
  </section>

