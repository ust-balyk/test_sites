  <?php //dump(session()->get('csrf_token')); ?> 
  
  <section class="superiority">
    <div class="container">
      <div class="row">
        
        <h1>ВЫБИРАЙ JAPAN-IN.RU!</h1>

        <div class="col-md-4 g-md-4">
          <div class="high_quality">
            <div class="high_quality_top">
              <img class="high_quality" src="<?= base_url(POCKET_STYLE .'/assets/quality/quality.png'); ?>"
                alt="high quality">
              <h5>Оригинальный товар!</h5>
            </div>
          </div>
        </div>
        <div class="col-md-4 g-md-4">
          <div class="high_quality">
            <div class="high_quality_top">
              <img class="high_quality" src="<?= base_url(POCKET_STYLE .'/assets/quality/quality.png'); ?>"
                alt="high quality">
              <h5>Лучший срок годности!</h5>
            </div>
          </div> 
        </div>
        <div class="col-md-4 g-md-4">
          <div class="high_quality">
            <div class="high_quality_top">
              <img class="high_quality" src="<?= base_url(POCKET_STYLE .'/assets/quality/quality.png'); ?>"
                alt="high quality">
              <h5>Качественная логистика!</h5>
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

  <section class="carouse-promo">
    <div class="container">
      <div class="slider-header">
        <a href="#" class="btn btn-sm btn-outline-secondary promo">
          <h5>товары по акции</h5>
        </a>
        <div class="slider-btn-control">
          <span class="prev-btn"><i class="fa-solid fa-chevron-left"></i></span>
          <span class="next-btn"><i class="fa-solid fa-chevron-right"></i></span>
        </div>
      </div><!--slider-header-->
      
      <div class="owl-carousel owl-theme" id="slider-promo">
        <div class="product-card"><!--promo-card-->
          <a href="#">
            <div class="product-card-img">
              <img src="<?= base_url('/images/for-face/11642.jpg'); ?>" alt="">
            </div>
          </a>
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
          </div>
        </div><!--product-card-->

        <div class="product-card">
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
              33 000р<del>35 000р</del>
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

        <div class="product-card">
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
              33 000р<del>35 000р</del>
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

        <div class="product-card">
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
              33 000р<del>35 000р</del>
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

      </div><!--#slider-promo-->
    </div><!--container-->
  </section><!--carousel-promo-->

  <section class="categories">
    <div class="container categories">
    
    <h3 class="text-center categories-title">Японская косметика</h3>
    
    <a href="https://ru.wikipedia.org/wiki/%D0%A1%D1%83%D0%B4%D0%B7%D1%83%D0%BA%D0%B8_%D0%A5%D0%B0%D1%80%D1%83%D0%BD%D0%BE%D0%B1%D1%83" target="_blank">
      <p class="mb-0">Хризантема в японской культуре является символом долголетия, счастья и мудрости.</p>
    </a>
    <div class="row">
      <div class="col-md-6 category">
        <div class="image-container">
          <a href="#">
            <picture>
              <source media="(min-width: 768px)" 
                    srcset="<?= base_url(POCKET_STYLE .'/assets/categories/1.jpg'); ?>" alt="">
                <div class="category_left">
                  <h5>декоративная косметика</h5>
                </div>
              <img src="<?= base_url(POCKET_STYLE .'/assets/categories/white.jpg'); ?>" alt="">
            </picture>
          </a>
        </div>
      </div>

      <div class="col-md-6 category">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/2.jpg'); ?>" alt="">
              <div class="category_right"><h5>для тела</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-6 category left">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/3.jpg'); ?>" alt="">
              <div class="category_left"><h5>для лица</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-6 category">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/4.jpg'); ?>" alt="">
              <div class="category_right"><h5>для полости рта</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-6 category">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/5.jpg'); ?>" alt="">
              <div class="category_left"><h5>для волос</h5></div>
          </a>
        </div>
      </div>
  
      <div class="col-md-6 category">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/6.jpg'); ?>" alt="">
              <div class="category_right"><h5>для рук</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-6 category">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/7.jpg'); ?>" alt="">
              <div class="category_left"><h5>для ног</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-6 category">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/8.jpg'); ?>" alt="">
              <div class="category_right"><h5>подарочные наборы</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-6 category">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/9.jpg'); ?>" alt="">
              <div class="category_left"><h5>ароматерапия</h5></div>
          </a>
        </div>
      </div>

      <div class="col-md-6 category">
        <div class="image-container">
          <a href="#">
            <img src="<?= base_url(POCKET_STYLE .'/assets/categories/10.jpg'); ?>" alt="">
              <div class="category_right"><h5>аксессуары</h5></div>
          </a>
        </div>
      </div>

    </div><!--row-->
      
    <div class="writer">
      <a href="https://ru.wikipedia.org/wiki/%D0%A1%D1%83%D0%B4%D0%B7%D1%83%D0%BA%D0%B8_%D0%A5%D0%B0%D1%80%D1%83%D0%BD%D0%BE%D0%B1%D1%83" target="_blank">
        <h5>鈴木春信</h5>
      </a>
 
    </div>
    </div>
  </section><!--categories-->

  <section class="carousel-promo popular">
    <div class="container">
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

  
