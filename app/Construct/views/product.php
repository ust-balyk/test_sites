<?php /* product_editable.html — вставьте в место вывода товара */ ?>
<?php if (session()->get('user.role') === 'master'): ?>
  <div id="admin-toolbar" class="admin-toolbar shadow-sm">
    <div class="container d-flex align-items-center gap-3">
      <span class="badge bg-warning text-dark">ADMIN MODE</span>
      <button class="btn btn-sm btn-primary" id="toggle-edit-btn">📝 Редактировать</button>
      <button class="btn btn-sm btn-success" id="new-product-btn">➕ Новый товар</button>
      <div id="format-tools" class="d-none border-start ps-3 ms-2">
        <button class="btn btn-sm btn-outline-light" data-cmd="bold"><b>B</b></button>
        <button class="btn btn-sm btn-outline-light" data-cmd="italic"><i>I</i></button>
        <button class="btn btn-sm btn-outline-light" data-cmd="insertUnorderedList">• Список</button>
        <button class="btn btn-sm btn-warning" data-cmd="removeFormat" title="Сбросить оформление">
          <i class="fa-solid fa-eraser"></i>
        </button>
        <button class="btn btn-sm btn-danger ms-3" id="save-all-btn">💾 Сохранить ВСЁ</button>
      </div>
    </div>
  </div>
  <style>
    .admin-toolbar{position:fixed;top:0;left:0;width:100%;background:#212529;z-index:1050;padding:8px 0;color:#fff}
    [contenteditable="true"]{outline:2px dashed #0d6efd;background:rgba(13,110,253,0.05)}
    .edit-image-hover{cursor:pointer;position:relative}
    .edit-image-hover::after{content:"Сменить фото";position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);color:#fff;display:flex;align-items:center;justify-content:center;opacity:0;transition:.3s}
    .edit-image-hover:hover::after{opacity:1}
    .navbar.fixed-top{top:40px}
  </style>
<?php endif; ?>

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
              <a href="/cosmetics/<?= $product['slug'] ?>"><?= hsc($product['category']) ?></a>
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
                data-outer="<?= hsc($product['outer_id']) ?>"><?= hsc($product['title']) ?></h1>
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
  <input type="hidden" id="admin-product-id" value="<?= hsc($product['outer_id']) ?>">
<?php endif; ?>
