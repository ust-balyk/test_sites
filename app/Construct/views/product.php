<?= db()->user_back(); ?>
<?php if (!empty($product)): ?>
  <section class="product">
    <div class="container product">
      <div class="container justify-content-between breadcrumb">
        <nav class="breadcrumb" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="/">
                <img class="link_to_home" src="<?= base_url(POCKET_STYLE.'/favicon/home.svg'); ?>"
                    alt="link_to_the_home_page">
              </a>
            </li>
            <li class="breadcrumb-item">
              <a href="/cosmetics">косметика</a>
            </li>
            <li class="breadcrumb-item">
              <a href="/cosmetics/<?= $product['slug'] ?>"><?= $product['category'] ?></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              арт.<?= $product['outer_id'] ?>
            </li>
          </ol>
        </nav>
        <div class="d-none d-md-block">
          <a href="/cosmetics/<?= $product['slug'] ?>" class="btn btn-sm btn-outline-secondary back_link">
            <h5>вернуться в категорию</h5>
          </a>
        </div>
      </div>

      <div class="row product">
        <div class="col-md-6">
          <div class="product_image">
            <img src="<?= $product['image'] ?>" onerror="this.onerror=null; this.src='/images/onerror.webp'" 
              alt="<?= $product['title'] ?>">
          </div>
        </div>
        <div class="col-md-6">
          <div class="product_content">
            <div class="product-details">
              <div class="product-rating">
                <span class="rating">
                  <i class="fa-solid fa-star active"></i>
                  <i class="fa-solid fa-star active"></i>
                  <i class="fa-solid fa-star active"></i>
                  <i class="fa-solid fa-star active"></i>
                  <i class="fa-solid fa-star"></i>
                </span>
                <a htef="#" class="product_review_count">(12) отзывов</a>
              </div>
              <h3 class="product-title"><?= $product['title'] ?></h3>
              <div class="distance"></div>
              <div class="product-price">
                <?php if ($product['price']) {
                        echo $product['price'];
                      } else if ($product['price'] == '') {
                        echo $product['new_price'].'<del>'.$product['old_price'].'</del>';
                      } else {
                        echo ' ';
                      }
                ?>
              </div>
              <div class="product-buttons">
                <button class="btn btn btn-outline-secondary to-favorites">
                  <h5>отложить</h5>
                </button>
                <button class="btn btn-outline-secondary to-cart">
                  <h5>купить</h5>
                </button>
              </div>
            </div>
          </div><!-- /pdoduct_content -->
        </div>
      </div><!-- /row product -->

      <div class="row description">
        <div class="col-12">
          <ul class="nav nav-pills flex-column flex-sm-row product-desc-tabs" 
                id="product-desc-tabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="flex-sm-fill text-sm-center nav-link active" id="description-tab" data-bs-toggle="tab"
                      data-bs-target="#description-tab-pane" type="button" role="tab"
                      aria-controls="description-tab-pane" aria-selected="true"><h5>описание</h5>
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="flex-sm-fill text-sm-center nav-link" id="reviews-tab" data-bs-toggle="tab"
                      data-bs-target="#reviews-tab-pane" type="button" role="tab"
                      aria-controls="reviews-tab-pane" aria-selected="false"><h5>отзывы</h5>
              </button>
            </li>
          </ul>

          <div class="tab-content product-desc-tabs-content" id="product-desc-tabs-content">

            <div class="tab-pane fade show active" id="description-tab-pane" role="tabpanel"
                  aria-labelledby="description-tab" tabindex="0">
              <div class="category-description clearfix">
                <?php 
                      $description = preg_replace('~&#13;~', '', (string)$product['description']);
                      $description = preg_replace('~</?pre\b[^>]*>~i', '', $description);
                      $description = preg_replace('~</br>i~', '', $description);
                      $description = preg_replace('#<img\b[^>]*>#i', '', $description);
                      $description = preg_replace('~<font\b[^>]*>~i', '', $description);
                      $description = preg_replace('~</font>~i', '', $description);
                      $description = preg_replace('#</?div\b[^>]*>#i', '', $description);
                      // удаляет <p>...</p>, где внутри только пробельные символы
                      $description = preg_replace('#<p\b[^>]*>(?:\s|&nbsp;|\x{00A0})*</p>#iu', '', $description);
                      // удалить все атрибуты у всех тегов
                      $description = preg_replace_callback('#<([a-z0-9]+)([^>]*)>#i', function($m){
                        return "<{$m[1]}>";
                      }, $description);
                      $description = preg_replace('/<script\b[^>]*>([\s\S]*?)<\/script>/i', '', $description);
                      $bad = ['▼','•','・','▸','▪','►','◦','◆'];
                      $description = str_replace($bad, '', $description);
                      //$description = preg_replace('/\s{2,}/u',' ', $description); // заменить пробелы на один
                      //$description = str_replace(["\r","\n","\t"],'',$description);
                      echo "<div class=\"description\">$description</div>";
                      

                    ?>
              </div>
              <!--div>
                <h2>статья для seo</h2>
                <p><img src="<?= $product['image'] ?>" class="note-float-left"
                        style="width: 25%;" alt=""></p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae vitae accusamus
                    quasi quos
                    laboriosam blanditiis, soluta, labore voluptates, ad magni omnis facilis amet
                    illo voluptatum
                    accusantium error voluptatibus eveniet inventore.</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi velit praesentium
                    repudiandae a
                    quam, qui assumenda soluta dolores officiis tempora cum, consectetur optio? Id
                    adipisci
                    necessitatibus, aliquid nihil minus laborum.</p>
                <p>Similique, culpa veniam ullam voluptas maiores, aliquid repellendus ipsum
                    suscipit eligendi enim
                    natus iusto dolore deleniti earum cum labore accusantium quas numquam quo eos
                    saepe fugiat,
                    blanditiis rerum quisquam! Placeat!</p>
              </div-->
            </div>

            <div class="tab-pane fade" id="reviews-tab-pane" role="tabpanel" 
                  aria-labelledby="reviews-tab" tabindex="0">
              <div class="row">
                <!-- Секция чтения отзывов -->
                <div class="col-md-7">
                    
                  <!-- Отзыв 1 -->
                  <div class="mb-3 my_card review">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Иван Иванов</h5>
                        <div class="star-rating">★★★★★</div>
                      </div>
                      <small class="text-muted">10 февраля 2026</small>
                      <p class="card-text mt-2">Отличный товар! Качество превзошло ожидания. 
                        Очень доволен покупкой.</p>
                    </div>
                  </div>

                  <!-- Отзыв 2 -->
                  <div class="mb-3 my_card review">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Елена Петрова</h5>
                        <div class="star-rating">★★★★☆</div>
                      </div>
                      <small class="text-muted">05 февраля 2026</small>
                      <p class="card-text mt-2">Хорошее соотношение цена/качество. Доставка быстрая.</p>
                    </div>
                  </div>

                  <div class="mb-3 my_card review">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Елена Петрова</h5>
                        <div class="star-rating">★★★★☆</div>
                      </div>
                      <small class="text-muted">05 февраля 2026</small>
                      <p class="card-text mt-2">
                        Хорошее соотношение цена/качество. Доставка быстрая.
                        Хорошее соотношение цена/качество. Доставка быстрая.
                        Хорошее соотношение цена/качество. Доставка быстрая.
                      </p>
                    </div>
                  </div>

                </div><!--col-md-8-->

                <!-- Секция формы создания отзыва -->
                <div class="col-md-5 mt-0">
                  <div class="my_card">
                    <!--h5 class="mb-3">оставить отзыв</h5-->
                    <form class="reply_form" action="">
                      <!--div class="mb-3">
                        <label for="userName" class="form-label">Ваше имя</label>
                        <input type="text" class="form-control" id="userName" placeholder="">
                      </div>
                            
                      <div class="mb-3">
                        <label for="userRating" class="form-label">Рейтинг</label>
                        <select class="form-select" id="userRating">
                          <option selected>выберите оценку</option>
                          <option value="5">★★★★★ (Отлично)</option>
                          <option value="4">★★★★☆ (Хорошо)</option>
                          <option value="3">★★★☆☆ (Нормально)</option>
                          <option value="2">★★☆☆☆ (Плохо)</option>
                          <option value="1">★☆☆☆☆ (Ужасно)</option>
                        </select>
                      </div--> 

                      <div class="mb-3 stars">
                        <p class="mb-0">Ваша оценка</p>
                        <input type="radio" id="star-5" name="rating" value="5">
                        <label for="star-5"></label>
                        <input type="radio" id="star-4" name="rating" value="4">
                        <label for="star-4"></label>
                        <input type="radio" id="star-3" name="rating" value="3">
                        <label for="star-3"></label>
                        <input type="radio" id="star-2" name="rating" value="2">
                        <label for="star-2"></label>
                        <input type="radio" id="star-1" name="rating" value="1">
                        <label for="star-1"></label>
                      </div>

                      <div class="mb-3">
                        <label for="comment" class="form-label">Комментарий</label>
                        <textarea class="form-control" id="comment" rows="4" placeholder=""></textarea>
                      </div>
                
                      <button type="submit" class="btn btn-primary w-100 recording">сохранить</button>
                    </form>
                  </div>
                </div>

              </div>
            </div><!-- отзывы -->

          </div> 
        </div>
      </div><!--description-->
    </div><!--container product-->
  </section>
<?php endif; ?>

<?php if (!empty($related_products)): ?>
  <section class="carouse-promo">
    <div class="container promo">
      <div class="slider-header">
        <a href="/cosmetics/<?= $product['slug'] ?>" class="btn btn-sm btn-outline-secondary promo">
          <h5>похожие продукты</h5>
        </a>
        <div class="slider-btn-control">
          <span class="prev-btn"><i class="fa-solid fa-chevron-left"></i></span>
          <span class="next-btn"><i class="fa-solid fa-chevron-right"></i></span>
        </div>
      </div>

      <div class="owl-carousel owl-theme" id="slider-product">
      <?php foreach ($related_products as $product): ?>
        <div class="product-card" itemscope itemtype="https://schema.org/Product">
          <?php if (empty($product['price']) && empty($product['new_price'])
                    && empty($product['old_price'])) { 
                  echo '<div class="product_expected">
                          <p>ожидается</p>
                        </div>';
                } else if ($product['new_price']) {
                  echo '<div class="discounted_product">
                          <p>акция!</p>
                        </div>';
                }
          ?>
          <a href="/cosmetics/<?= $product['slug'] ?>/product/<?= $product['outer_id'] ?>">
            <div class="product-card-img">
              <img src="<?= $product['image'] ?>" onerror="this.onerror=null; this.src='/images/onerror.webp'"
                loading="lazy" alt="натуральная японская косметика и витамины для долголетия" itemprop="image">
            </div>
          </a>
          <div class="product-card-details">
            <h6 class="product-card-title" itemprop="name">
              <a href="/cosmetics/<?= $product['slug'] ?>/product/<?= $product['outer_id'] ?>">
                <?= $product['title'] ?>
              </a>
            </h6>
            <div class="product-card-price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                <p style="margin:0;padding:0;" itemprop="price">
                <?php if ($product['price']) {
                            echo $product['price'];
                          } else if ($product['price'] == '') {
                            echo $product['new_price'].'<del>'.$product['old_price'].'</del>';
                          } else {
                            echo ' ';
                          }
                ?>
                </p>
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
      <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

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

      <div class="block" style="margin-top: 20px">
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

      <div class="block" style="margin-top: 20px">
        <h6 style="color: #4295e4; margin-top: 20px">ВОЗВРАТ</h6>
        <p style="margin-bottom: 40px">
        Срок возврата товара надлежащего качества в соответствии с перечнем товаров 
        подлежащих возврату и обмену, составляет 30 дней с момента получения товара. 
        Возврат переведенных средств, производится на Ваш банковский счет в 
        течение 5—30 рабочих дней<br> 
        (<strong>сроки перевода зависят от правил Вашего банка</strong>).
      </div>
    </div>
  </section>
<script>localStorage.setItem('location', window.location.href);</script>   
