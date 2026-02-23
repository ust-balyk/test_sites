<?=db()->user_back();?>
  <section class="product">
    <div class="container product">

      <div class="container justify-content-between breadcrumb">
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
        <div class="d-none d-md-block">
          <a href="#" class="btn btn-sm btn-outline-secondary back_link" onclick="history.back(); return false;">
            <h5>вернуться в категорию</h5></a>
        </div>
      </div>

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

      <div class="row description">
        <div class="col-12">

          <ul class="nav nav-pills flex-column flex-sm-row product-desc-tabs" id="product-desc-tabs" role="tablist">
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
                <h2>Lorem ipsum dolor sit amet.</h2>
                <p><img src="assets/img/categories/category-desc-1.jpg" class="note-float-right"
                        style="width: 25%;" alt=""></p>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolor maxime illo rem
                    accusamus nulla
                    veritatis, quae assumenda aspernatur quo aperiam. Tenetur itaque dolorem
                    distinctio architecto
                    voluptatum nobis earum similique esse!</p>
                <ul>
                  <li>Межкомнатую дверь</li>
                  <li>Деревянную дверь</li>
                  <li>Межкомнатую дверь</li>
                  <li>Деревянную дверь</li>
                </ul>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam perferendis
                    labore, enim, aut
                    corporis assumenda veniam natus similique dolore repellat explicabo in corrupti
                    reprehenderit,
                    nemo tempore ipsam totam a rerum.</p>
                <p>Eaque id cumque optio fugit amet. Ullam fugit omnis animi voluptatem quos,
                    temporibus obcaecati
                    explicabo minima laboriosam ipsum. Voluptates possimus, incidunt officia
                    suscipit quibusdam
                    minus cum aliquid quis perferendis exercitationem?</p>

                <h2>Lorem ipsum dolor sit amet.</h2>
                <p><img src="assets/img/categories/category-desc-2.jpg" class="note-float-left"
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
              </div>
            </div>

            <div class="tab-pane fade" id="reviews-tab-pane" role="tabpanel" aria-labelledby="reviews-tab" tabindex="0">
              <div class="row">
                <div class="col-lg-4 order-lg-2 mb-lg-0 mb-4">
                  <button class="btn btn-primary d-block d-lg-none w-100" type="button"
                      data-bs-toggle="collapse" data-bs-target="#reply-form" aria-expanded="false"
                      aria-controls="reply-form">
                      Оставить отзыв
                  </button>
                  <div class="reply-form collapse" id="reply-form">
                    <form action="">
                      <div class="star-rating">
                        <p>Ваша оценка</p>
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
                        <label for="name" class="form-label">Ваше имя</label>
                        <input type="text" class="form-control" id="name"  placeholder="Введите Ваше имя">
                      </div>
                      <div class="mb-3">
                        <label for="email" class="form-label">Ваш Email</label>
                        <input type="email" class="form-control" id="email"  placeholder="Введите Ваш Email">
                      </div>
                      <div class="mb-3">
                        <label for="comment" class="form-label">Ваш комментарий</label>
                        <textarea class="form-control" id="comment"  placeholder="Введите Ваш комментарий" rows="4"  style="resize: none;"></textarea>
                      </div>
                      <button type="submit" class="btn btn-primary w-100">Оставить  отзыв</button>
                    </form>
                  </div>
                </div>

                <div class="col-lg-8 reviews order-lg-1">
                  <div class="card review-card">
                    <div class="card-header">
                      <div class="review-author">Андрей Попенко</div>
                      <div class="review-created">20 августа, 2021</div>
                      <div class="review-rating">
                        <span class="product-rating">
                          <i class="fa-solid fa-star active"></i>
                          <i class="fa-solid fa-star active"></i>
                          <i class="fa-solid fa-star active"></i>
                          <i class="fa-solid fa-star"></i>
                          <i class="fa-solid fa-star"></i>
                        </span>
                      </div>
                    </div>
                    <div class="card-body">
                      <p class="card-text">Et feugiat eu scelerisque nulla mattis. At et enim
                          dui mauris. Nisi, hendrerit dictum consequat tristique sed. Est
                          ultrices etiam in lorem nulla a.Et feugiat eu scelerisque nulla
                          mattis. At et enim dui mauris. Nisi</p>
                      <div>
                        <a href="#" class="review-card-reply">
                          <i class="fa-regular fa-share-from-square"></i>
                          Ответить
                        </a>
                        <a href="#" class="review-card-answers">
                          <i class="fa-regular fa-message"></i>
                          1 комментарий
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-8 reviews order-lg-1">

                  <div class="card review-card">
                    <div class="card-header">
                      <div class="review-author">Андрей Попенко</div>
                      <div class="review-created">20 августа, 2021</div>
                      <div class="review-rating">
                        <span class="product-rating">
                          <i class="fa-solid fa-star active"></i>
                          <i class="fa-solid fa-star active"></i>
                          <i class="fa-solid fa-star active"></i>
                          <i class="fa-solid fa-star"></i>
                          <i class="fa-solid fa-star"></i>
                        </span>
                      </div>
                    </div>
                    <div class="card-body">
                      <p class="card-text">Et feugiat eu scelerisque nulla mattis. At et enim
                          dui mauris. Nisi, hendrerit dictum consequat tristique sed. Est
                          ultrices etiam in lorem nulla a.Et feugiat eu scelerisque nulla
                          mattis. At et enim dui mauris. Nisi</p>
                      <div>
                        <a href="#" class="review-card-reply">
                          <i class="fa-regular fa-share-from-square"></i>
                          Ответить
                        </a>
                        <a href="#" class="review-card-answers">
                          <i class="fa-regular fa-message"></i>
                          1 комментарий
                        </a>
                      </div>
                    </div>
                  </div>

                  <div class="card review-card">
                    <div class="card-header">
                      <div class="review-author">Андрей Попенко</div>
                      <div class="review-created">20 августа, 2021</div>
                      <div class="review-rating">
                        <span class="product-rating">
                          <i class="fa-solid fa-star active"></i>
                          <i class="fa-solid fa-star active"></i>
                          <i class="fa-solid fa-star active"></i>
                          <i class="fa-solid fa-star"></i>
                          <i class="fa-solid fa-star"></i>
                        </span>
                      </div>
                    </div>
                    <div class="card-body">
                      <p class="card-text">Et feugiat eu scelerisque nulla mattis. At et enim
                          dui mauris. Nisi, hendrerit dictum consequat tristique sed. Est
                          ultrices etiam in lorem nulla a.Et feugiat eu scelerisque nulla
                          mattis. At et enim dui mauris. Nisi</p>
                      <div>
                        <a href="#" class="review-card-reply">
                          <i class="fa-regular fa-share-from-square"></i>
                          Ответить
                        </a>
                        <a href="#" class="review-card-answers">
                          <i class="fa-regular fa-message"></i>
                          1 комментарий
                        </a>
                      </div>
                    </div>
                  </div>

                  <p class="text-center">
                    <a href="#" class="reply-load-more">
                      <i class="fa-solid fa-rotate-right"></i>
                      Показать еще
                    </a>
                  </p>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
          
    </div><!--container product-->
  </section>

  <section class="carouse-promo">
    <div class="container promo">
      <div class="slider-header">
        <a href="#" class="btn btn-sm btn-outline-secondary promo">
          <h5>похожие продукты</h5>
        </a>
        <div class="slider-btn-control">
          <span class="prev-btn"><i class="fa-solid fa-chevron-left"></i></span>
          <span class="next-btn"><i class="fa-solid fa-chevron-right"></i></span>
        </div>
      </div>
      
      <div class="owl-carousel owl-theme" id="slider-promo">

        <div class="product-card" itemscope itemtype="https://schema.org/Product"><!--product-card-->
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
        <div class="product-card" itemscope itemtype="https://schema.org/Product"><!--product-card-->
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
        <div class="product-card" itemscope itemtype="https://schema.org/Product"><!--product-card-->
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
        <div class="product-card" itemscope itemtype="https://schema.org/Product"><!--product-card-->
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
        <div class="product-card" itemscope itemtype="https://schema.org/Product"><!--product-card-->
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
        <div class="product-card" itemscope itemtype="https://schema.org/Product"><!--product-card-->
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
      </div>
    </div>
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
