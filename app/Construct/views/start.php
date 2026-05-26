  <section class="superiority">
    <div class="container superiority">
      <div class="row superiority">
        <h1 class="header_post">
          Добро пожаловать в мир красоты на Japan-in.Ru!
        </h1><br>
        <h3>
          Пусть каждый день начинается с личного ритуала, наполняющего тебя любовью к самой себе и
          сиянием очарования!
        </h3>
        <div class="col-md-4 g-md-4">
          <div class="high_quality">
            <div class="high_quality_top">
              <h3>本物</h3>
              <h5>Оригинальный товар!</h5>
            </div>
          </div>
        </div>
        <div class="col-md-4 g-md-4">
          <div class="high_quality">
            <div class="high_quality_top">
              <h3>最新ロット</h3>
              <h5>Лучший срок годности!</h5>
            </div>
          </div> 
        </div>
        <div class="col-md-4 g-md-4">
          <div class="high_quality">
            <div class="high_quality_top">
              <h3>丁寧な梱包</h3>
              <h5>Качественная логистика!</h5>
            </div>
          </div> 
        </div>    
      </div><!--row-->
    </div><!--container-->
  </section><!--superiority-->

  <section class="achievements">
    <div class="container achievements">
      <div class="row achievements">        
        <div class="col-md-4 col-sm-6 achievement-item left">
            <span class="counter-num" data-from="0">5976</span>
            <p class="counter-text">Довольных покупателей</p>
        </div>

        <div class="col-md-4 col-sm-6 achievement-item">
            <span class="counter-num">~1000</span>
            <p class="counter-text">Продуктов на выбор</p>
        </div>

        <div class="col-md-4 col-sm-12 achievement-item right">
            <span class="counter-num">12</span>
            <p class="counter-text">Лет на рынке</p>
        </div>
      </div>
    </div>
  </section><!--achievements-->

  <?//php dump($_SESSION) ?> 
  <!--section class="carouse-promo">
    <div class="container">
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
        <div class="product-card">
          <a href="#">
            <div class="product-card-img">
              <img src="<?//= base_url('/images/for-face/11642.jpg'); ?>" alt="">
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
        </div>
      </div>
    </div>
  </section--><!--carousel-promo-->

<?php //dump($_SESSION); ?>
<?php //dump($_SERVER['REQUEST_URI']); ?>
<?php //dump($_COOKIE); ?>

  <section class="categories">
    <div class="container categories">
      <h3 class="text-center categories-title">Японская косметика</h3>
      
      <a href="https://ru.wikipedia.org/wiki/%D0%A1%D1%83%D0%B4%D0%B7%D1%83%D0%BA%D0%B8_%D0%A5%D0%B0%D1%80%D1%83%D0%BD%D0%BE%D0%B1%D1%83" target="_blank">
        <p class="mb-0">Хризантема в японской культуре является символом долголетия, счастья и мудрости.</p>
      </a>
      <div class="row">

        <div class="col-md-6 category">
          <div class="image-container">
            <a href="/cosmetics/makeup">
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
            <a href="/cosmetics/for-body">
              <img src="<?= base_url(POCKET_STYLE .'/assets/categories/2.jpg'); ?>" alt="">
                <div class="category_right"><h5>для тела</h5></div>
            </a>
          </div>
        </div>

        <div class="col-md-6 category">
          <div class="image-container">
            <a href="/cosmetics/for-face">
              <img src="<?= base_url(POCKET_STYLE .'/assets/categories/3.jpg'); ?>" alt="">
                <div class="category_left"><h5>для лица</h5></div>
            </a>
          </div>
        </div>

        <div class="col-md-6 category" id="new_top"><!-- new_top -->
          <div class="image-container">
            <a href="/cosmetics/for-oral-cavity">
              <img src="<?= base_url(POCKET_STYLE .'/assets/categories/4.jpg'); ?>" alt="">
                <div class="category_right"><h5>для полости рта</h5></div>
            </a>
          </div>
        </div>

        <div class="col-md-6 category">
          <div class="image-container">
            <a href="/cosmetics/for-hair">
              <img src="<?= base_url(POCKET_STYLE .'/assets/categories/5.jpg'); ?>" alt="">
                <div class="category_left"><h5>для волос</h5></div>
            </a>
          </div>
        </div>
    
        <div class="col-md-6 category">
          <div class="image-container">
            <a href="/cosmetics/for-hands">
              <img src="<?= base_url(POCKET_STYLE .'/assets/categories/6.jpg'); ?>" alt="">
                <div class="category_right"><h5>для рук</h5></div>
            </a>
          </div>
        </div>

        <div class="col-md-6 category">
          <div class="image-container">
            <a href="/cosmetics/for-feet">
              <img src="<?= base_url(POCKET_STYLE .'/assets/categories/7.jpg'); ?>" alt="">
                <div class="category_left"><h5>для ног</h5></div>
            </a>
          </div>
        </div>

        <div class="col-md-6 category">
          <div class="image-container">
            <a href="/cosmetics/gift-set">
              <img src="<?= base_url(POCKET_STYLE .'/assets/categories/8.jpg'); ?>" alt="">
                <div class="category_right"><h5>подарочные наборы</h5></div>
            </a>
          </div>
        </div>

        <div class="col-md-6 category">
          <div class="image-container">
            <a href="/cosmetics/aromatherapy">
              <img src="<?= base_url(POCKET_STYLE .'/assets/categories/9.jpg'); ?>" alt="">
                <div class="category_left"><h5>ароматерапия</h5></div>
            </a>
          </div>
        </div>

        <div class="col-md-6 category">
          <div class="image-container">
            <a href="/cosmetics/accessories">
              <img src="<?= base_url(POCKET_STYLE .'/assets/categories/10.jpg'); ?>" alt="">
                <div class="category_right"><h5>аксессуары</h5></div>
            </a>
          </div>
        </div>
        
      </div>
    </div><!--container categories-->
      
    <div class="writer">
      <a href="https://ru.wikipedia.org/wiki/%D0%A1%D1%83%D0%B4%D0%B7%D1%83%D0%BA%D0%B8_%D0%A5%D0%B0%D1%80%D1%83%D0%BD%D0%BE%D0%B1%D1%83" target="_blank">
        <h5>鈴木春信</h5>
      </a>
    </div>
  </section><!--/categories-->
<?php if (!empty($discounted_products)): ?>
  <section class="carousel-promo popular">
    <div class="container">
      <div class="slider-header">
        <a href="/cosmetics/discount" class="btn btn-sm btn-outline-secondary popular">
          <span>товары по акции</span>
        </a>
        <div class="slider-btn-control">
          <span class="prev-btn"><i class="fa-solid fa-chevron-left"></i></span>
          <span class="next-btn"><i class="fa-solid fa-chevron-right"></i></span>
        </div>
      </div><!--slider-header-->
      <div class="owl-carousel owl-theme" id="slider-popular">
      <?php foreach ($discounted_products as $product): ?>
        <div class="product-card .popular_product">
          <div class="discounted_product">
            <p>акция!</p>
          </div>
          <a href="/cosmetics/<?= $product['slug']; ?>/product/<?= $product['outer_id']; ?>">
            <div class="product-card-img">
              <img src="<?= $product['image'] ?>" onerror="this.onerror=null; this.src='/images/onerror.webp'"
                 alt="<?= $product['title'] ?>">
            </div>
          </a>
          <div class="product-card-details">
            <h6 class="product-card-title">
              <a href="/cosmetics/<?= $product['slug']; ?>/product/<?= $product['outer_id']; ?>">
                <?= $product['title'] ?>
              </a>
            </h6>
            <div class="product-card-price">
              <?= $product['new_price'] ?><del><?= $product['old_price'] ?></del>
            </div>
            <div class="product-card-btns">
              <button class="btn btn btn-outline-secondary add-to-favorites"
                  style="background-color:transparent!important"
                data-id="<?= hsc($product['outer_id']) ?>"><i class="fa-solid fa-heart"></i>
              </button>
              <button class="btn btn-outline-secondary add-to-cart" style="background-color:transparent!important"
                    data-id="<?= hsc($product['outer_id']) ?>">
                <i class="fa-solid fa-cart-shopping 
                  <?= \App\Widgets\Cart\Cart::hasProductInCart(hsc($product['outer_id']))?'in_cart':'' ?>"></i>
                <div class="spinner-border loader d-none" 
                    style="width:2.2rem;height:2.2rem;margin-left:0.8rem;color:#90cdfb" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
      </div>
    </div><!--container-->
  </section> 
<?php endif; ?>
<?//php dump(\App\Widgets\Cart\Cart::getCart()); ?>
<script>localStorage.setItem('location', window.location.href);</script>
