<?php if (session()->get('user.role') === 'master' || session()->get('user.role') === 'assistant'): ?>
  <div id="admin-toolbar" class="admin-toolbar shadow-sm">
    <div class="container d-flex align-items-center gap-3">
      <button class="btn btn-sm btn-success" id="new-product-btn"
        style="border:1px solid grey">&nbsp;ДОБАВИТЬ ТОВАР&nbsp;</button>
      <button class="btn btn-sm btn-primary" id="toggle-edit-btn"
        style="border:1px solid grey">&nbsp;РЕДАКТИРОВАТЬ ТОВАР&nbsp;</button>

      <select id="category-select" name="category_mode" class="form-select d-inline-block w-auto d-none"
        style="border:1px solid grey">
        <option value="current">&nbsp;&nbsp;&nbsp;ДАННАЯ КАТЕГОРИЯ</option>
        <?php foreach ($data as $value): ?>
        <option value="<?= hsc($value['slug']) ?>" style="font-size: 1.8rem">
          <?= hsc($value['category']) ?>
        </option>
        <?php endforeach; ?>
      </select>
      <input type="hidden" name="slug-category" id="admin-slug-category" value="" autocomplete="off">

      <!-- Панель форматирования -->
      <div id="format-tools" class="d-none border-start ps-3 ms-2">
        <!-- Основные стили -->
        <div class="btn-group btn-group-sm me-2" role="group" style="border:1px solid grey">
          <button class="btn btn-sm btn-outline-light" id="strong" data-cmd="strong">
            <b>STRONG</b>
          </button>
          <button class="btn btn-sm btn-outline-light" id="italic" data-cmd="italic">
            <i>ITALIC</i>
          </button>
          <button class="btn btn-sm btn-outline-light" id="underline" data-cmd="underline">
            <u>UNDERLINE</u>
          </button>
          <button class="btn btn-sm btn-outline-light" id="strikethrough" data-cmd="strikethrough">
            <s>STRIKE</s>
          </button>
        </div>

        <!-- Цвета -->
        <div class="btn-group btn-group-sm me-2" role="group" style="border:1px solid grey">
          <button class="btn btn-sm btn-outline-light dropdown-toggle" 
            id="text-color-btn" data-bs-toggle="dropdown">ЦВЕТ ТЕКСТА 
          </button>
          <div class="dropdown-menu edit" style="border:1px solid grey; font-size: 2rem">
            <input type="color" class="form-control form-control-color m-2" id="text-color-picker" value="#000000">
            <button class="dropdown-item" data-cmd="textColor" data-color="#000000">чёрный</button>
            <button class="dropdown-item" data-cmd="textColor" data-color="#ff0000">красный</button>
            <button class="dropdown-item" data-cmd="textColor" data-color="#00ff00">зелёный</button>
            <button class="dropdown-item" data-cmd="textColor" data-color="#0000ff">синий</button>
          </div>

          <button class="btn btn-sm btn-outline-light dropdown-toggle" 
            id="bg-color-btn" data-bs-toggle="dropdown">ФОН ТЕКСТА 
          </button>
          <div class="dropdown-menu edit" style="border:1px solid grey; font-size: 2rem">
            <input type="color" class="form-control form-control-color m-2" id="bg-color-picker" value="#ffff00">
            <button class="dropdown-item" data-cmd="highlight" data-color="#ffff00">жёлтый</button>
            <button class="dropdown-item" data-cmd="highlight" data-color="#ffcccc">светло-красный</button>
            <button class="dropdown-item" data-cmd="highlight" data-color="#ccffcc">светло-зелёный</button>
            <button class="dropdown-item" data-cmd="highlight" data-color="#ccccff">светло-синий</button>
          </div>
        </div>

        <!-- Заголовки -->
        <div class="btn-group btn-group-sm me-2" role="group" style="border:1px solid grey">
          <button class="btn btn-sm btn-outline-light" id="h3" data-cmd="h3">H3</button>
          <button class="btn btn-sm btn-outline-light" id="h4" data-cmd="h4">H4</button>
          <button class="btn btn-sm btn-outline-light" id="h5" data-cmd="h5">H5</button>
        </div>

        <!-- Перенос строки -->
        <button class="btn btn-sm btn-outline-light me-2" id="br" data-cmd="br" style="border:1px solid grey">BR</button>

        <!-- Списки -->
        <div class="btn-group btn-group-sm me-2" role="group" style="border:1px solid grey">
          <button class="btn btn-sm btn-outline-light dropdown-toggle" id="list-type-btn" data-bs-toggle="dropdown">
            СПИСОК
          </button>
          <div class="dropdown-menu edit" style="border:1px solid grey; font-size: 2rem">
            <button class="dropdown-item" data-cmd="unorderedList" data-marker="disc">• диск</button>
            <button class="dropdown-item" data-cmd="unorderedList" data-marker="circle">○ круг</button>
            <button class="dropdown-item" data-cmd="unorderedList" data-marker="square">■ квадрат</button>
            <button class="dropdown-item" data-cmd="unorderedList" data-marker="none">без маркеров</button>
            <!-- Цвет маркера (например, тот же цветпикер, но отдельный) -->
            <input type="color" id="marker-color-picker" value="#000000">
          </div>
        </div>

        <!-- Дополнительные функции -->
        <div class="btn-group btn-group-sm me-2" role="group" style="border:1px solid grey">
          <button class="btn btn-sm btn-outline-light" id="link" data-cmd="link">ССЫЛКА</button>
        </div>

        <!-- Очистка форматирования -->
        <button class="btn btn-sm btn-warning" id="remove" data-cmd="clear" style="border:1px solid grey">
          <i class="fa-solid fa-eraser"></i>
        </button>

        <!-- Сохранение -->
        <button class="btn btn-sm btn-danger ms-3" id="save-all-btn" 
          style="border:1px solid grey">&nbsp;СОХРАНИТЬ
        </button>
        <!-- Удаление -->
        <button class="btn btn-sm btn-danger d-none" id="delete-product-btn"
          style="border:1px solid grey">&nbsp;УДАЛИТЬ
        </button>
      </div>
    </div>
  </div>
  <style>
    .admin-toolbar { 
      position: fixed; 
      top: 0; 
      left: 0; 
      width: 100%; 
      background: #212529; 
      z-index: 1050; 
      padding: 8px 0; 
      color: white; 
    }
    [contenteditable="true"] { 
      outline: 2px dashed #0d6efd !important; 
      background: rgba(13, 110, 253, 0.05); 
    }
    .edit-image-hover { cursor: pointer; position: relative; }
    .edit-image-hover::after { 
      content: "СМЕНИТЬ ИЗОБРАЖЕНИЕ"; 
      position: absolute; 
      top: 0; 
      left: 0; 
      width: 100%; 
      height: 100%; 
      background: rgba(0,0,0,0.5); 
      color: white; 
      display: flex; 
      align-items: center; 
      justify-content: center; 
      opacity: 0; 
      transition: 0.3s; 
    }
    #toggle-edit-btn {
      font-family: "Arial", sans-serif;
      font-size: 1.4rem;
      font-weight: 400;
      color: #fff;
      background-color: green;
      border-color: #444;
    }
    #new-product-btn {
      font-family: "Arial", sans-serif;
      font-size: 1.4rem;
      font-weight: 400;
      color: #fff;
      background-color: orange;
      border-color: #444;
    }
    #category-select, #underline, #strikethrough, #text-color-btn, #bg-color-btn,
    #strong, #italic, #link, #list-type-btn, #h3, #h4, #h5, #br, #remove {
      font-family: "Arial", sans-serif;
      font-size: 1.4rem;
      font-weight: 400;
      color: #fff;
      background-color: #212529;
      border-color: #444;
    }
    #category-select option{
      color: #000; /* часто зависит от браузера/ОС */
    }
    #save-all-btn {
      font-family: "Arial", sans-serif;
      font-size: 1.4rem;
      font-weight: 400;
      color: #fff;
      background-color: blue;
      border-color: #444;
    }
    #delete-product-btn {
      font-family: "Arial", sans-serif;
      font-size: 1.4rem;
      font-weight: 400;
      color: #fff;
      background-color: red;
      border-color: #444;
    }
    .edit-image-hover:hover::after { opacity: 1; }
    .navbar.fixed-top{ top:4.5rem; }
    section.product { margin-top: 14rem !important; }
  </style>
<?php endif; ?>
<?php if (!empty($product)): ?>
  <section class="product" data-slug="<?= hsc($product['slug']) ?>">
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
              <a href="/cosmetics/<?= $product['slug'] ?>">
                <?= hsc($product['category']) ?>
              </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              арт.<?= hsc($product['outer_id']) ?>
            </li>
          </ol>
        </nav>
        <div class="d-none d-md-block">
          <a href="/cosmetics/<?= hsc($product['slug']) ?>" class="btn btn-sm btn-outline-secondary back_link">
            <span>вернуться в категорию</span>
          </a>
        </div>
      </div>
      <div class="row product">
        <div class="col-md-6">
          <div class="product_image" id="image-container">
            <img id="main-product-image" src="<?= hsc($product['image']) ?>"
                 onerror="this.onerror=null;this.src='/images/onerror.webp'"
                 alt="<?= hsc($product['title']) ?>">
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
                <a href="#" class="product_review_count">(12) отзывов</a>
              </div>
              <h1 class="product-title" id="edit-title" 
                data-outer="<?= hsc($product['outer_id']) ?>"><?= hsc($product['title']) ?>
              </h1>
              <div class="distance"></div>
              <div class="product-price" id="edit-price">
                <?php if ($product['price']): ?>
                  <?= hsc($product['price']) ?>
                <?php else: ?>
                  <?= hsc($product['new_price']) ?><del><?= hsc($product['old_price']) ?></del>
                <?php endif; ?>
              </div>
              <div class="product-buttons">
                <button class="btn btn-outline-secondary add-to-favorites" 
                      data-id="<?= hsc($product['outer_id']) ?>"><span>отложить</span></button>
                <button class="btn btn-outline-secondary add-to-cart" 
                      data-id="<?= hsc($product['outer_id']) ?>"><span>купить</span></button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row description">
        <div class="col-12">
          <ul class="nav nav-pills product-desc-tabs" id="product-desc-tabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="description-tab" 
                    data-bs-toggle="tab" data-bs-target="#description-tab-pane" type="button" 
                  role="tab"><span>описание</span>
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="reviews-tab" 
                    data-bs-toggle="tab" data-bs-target="#reviews-tab-pane" type="button" 
                  role="tab"><span>отзывы</span>
              </button>
            </li>
          </ul>

          <div class="tab-content product-desc-tabs-content">
            <div class="tab-pane fade show active" id="description-tab-pane" role="tabpanel">
              <div class="category-description clearfix" id="edit-description">
                <?= $product['description'] ?>
              </div>
            </div>

            <div class="tab-pane fade" id="reviews-tab-pane" role="tabpanel">
              <div class="row">
                <div class="col-md-7" id="reviews-list">
                  <!-- server-rendered existing reviews; each .review can be edited in-place -->
                  <?php foreach ($product['reviews'] ?? [] as $rv): ?>
                    <div class="mb-3 my_card review">
                      <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                          <h5 class="card-title mb-0 editable-review-author" 
                            contenteditable="false"><?= hsc($rv['author']) ?></h5>
                          <div class="star-rating"><?= hsc($rv['rating']) ?></div>
                        </div>
                        <small class="text-muted"><?= hsc($rv['date']) ?></small>
                        <p class="card-text mt-2 editable-review-text" 
                          contenteditable="false"><?= hsc($rv['text']) ?></p>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>

                <div class="col-md-5 mt-0">
                  <div class="my_card">
                    <form id="reply-form" action="">
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
                        <textarea class="form-control" id="comment" rows="4"></textarea>
                      </div>

                      <button type="button" id="add-review-btn" 
                        class="btn btn-primary w-100 recording"><span>сохранить</span>
                      </button>
                    </form>
                  </div>
                </div>

              </div>
            </div><!-- /reviews -->
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- hidden inputs managed by JS -->
  <input type="hidden" id="admin-product-id" value="<?= hsc($product['outer_id']) ?>" autocomplete="off">
<?php endif; ?>

<?php if (!empty($related_products)): ?>
  <section class="carouse-promo">
    <div class="container promo">
      <div class="slider-header">
        <a href="/cosmetics/<?= $product['slug'] ?>" class="btn btn-sm btn-outline-secondary promo">
          <span>похожие продукты</span>
        </a>
        <div class="slider-btn-control">
          <span class="prev-btn"><i class="fa-solid fa-chevron-left"></i></span>
          <span class="next-btn"><i class="fa-solid fa-chevron-right"></i></span>
        </div>
      </div>

      <div class="owl-carousel owl-theme" id="slider-product">
      <?php foreach ($related_products as $related_): ?>
        <div class="product-card">
        <?php if (empty($related_['price']) && empty($related_['new_price'])): ?>
          <div class="product_expected"><p>ожидается</p></div>
        <?php elseif ($related_['in_stock'] == 0): ?>
          <div class="product_expected"><p>ожидается</p></div>
        <?php elseif ($related_['new_price']): ?>
          <div class="discounted_product"><p>акция!</p></div>
        <?php endif; ?>
          <a href="/cosmetics/<?= $related_['slug'] ?>/product/<?= $related_['outer_id'] ?>">
            <div class="product-card-img">
              <img src="<?= hsc($related_['image']) ?>" onerror="this.onerror=null; this.src='/images/onerror.webp'"
                loading="lazy" alt="<?= hsc($related_['title']) ?? 
                'японская косметика и витамины для красоты и долголетия' ?>">
            </div>
          </a>
          <div class="product-card-details">
            <h6 class="product-card-title">
              <a href="/cosmetics/<?= $related_['slug'] ?>/product/<?= $related_['outer_id'] ?>">
                <?= hsc($related_['title']) ?>
              </a>
            </h6>
            <div class="product-card-price">
            <?php if (!empty($related_['price'])): ?>
              <span class="current-price"><?= $related_['price'] ?></span>
            <?php else: ?>
              <span class="new-price"><?= hsc($related_['new_price']) ?? '' ?></span>
              <del class="old-price"><?= hsc($related_['old_price']) ?? '' ?></del>
            <?php endif; ?>
            </div>
            <div class="product-card-btns" style="height:4.3rem">
            <?php if ($related_['in_stock']): ?>
              <button class="btn btn btn-outline-secondary add-to-favorites"
                data-id="<?= hsc($related_['outer_id']) ?>">
                <i class="fa-solid fa-heart"></i>
              </button>
              <button class="btn btn-outline-secondary add-to-cart" data-id="<?= hsc($related_['outer_id']) ?>" title="カートに追加">
                <i class="fa-solid fa-cart-shopping
                <?= \App\Widgets\Cart\Cart::hasProductInCart(hsc($product['outer_id']))?'in_cart':'' ?>"></i>
                <div class="spinner-border d-none"
                  style="width:2.2rem;height:2.2rem;margin-left:0.8rem;color:#90cdfb" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </button>
            <?php endif; ?>
            </div>
          </div>
        </div><!--product-card-->
      <?php endforeach; ?>
      </div>
    </div>
  </section-->
<?php endif; ?>

  <section class="delivery">
      <div class="container delivery">
        <div class="block">
          <h6 style="color: #4295e4; margin-top: 20px">ДОСТАВКА</h6>
          <p>
          Мы сотрудничаем с логистической компанией «Служба Доставки Экспресс-Курьер».<br> 
          <strong>
          Стоимость и сроки доставки рассчитываются автоматически и соответствуют тарифам перевозчика.
          </strong></p>
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
          <p style="margin-bottom: 40px">
        </div>

        <div class="block" style="margin-top: 20px">
          <h6 style="color: #4295e4; margin-top: 20px">ОПЛАТА</h6>
          <p><strong>
          Платежи по банковским картам проводятся в строгом соответствии с требованиями платежных систем.
          </strong></br>
          При оплате на сайте вы будете перенаправлены на защищённый платежный шлюз АО «Тинькофф Банк». 
          Оплата происходит через зашифрованный протокол SSL 
          <strong>
          без комиссии картой любого банка.
          </strong></p>
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
          </p>
        </div>
      </div>
    </section>
    <script>localStorage.setItem('location', window.location.href);</script>

