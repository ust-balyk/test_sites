  <section class="category">
    <div class="container category">
      
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
            <li class="breadcrumb-item active" aria-current="page">Категория</li>
          </ol>
        </nav>
      </div>

      <h1 class="text-center title_category">Категория</h1>

      <div class="row category">

        <div class="col-md-2 d-none d-md-block sidebar" style="height: 100%;">

          <div class="d-grid">
            <a href="#" class="btn btn-outline-primary"><h6>сбросить фильтры</h6></a>
          </div>

          <div class="filters">

            <div id="btn_activate_filters" class="d-grid">
              <a id="selector_link" href="#" class="">
                <h6 id="selector_text" class="">фильтры</h6>
              </a>
            </div>

            <div class="accordion" id="filters_accordion">
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#filter_1"
                      aria-expanded="true" aria-controls="filter_1">по действию
                  </button>
                </h2>
                <div id="filter_1" class="accordion-collapse collapse">
                <!--div id="filter_1" class="accordion-collapse collapse show"
                    data-bs-parent="#filters_accordion"-->
                  <div class="accordion-body">
                    <ul class="filters_list">
                      <li class="filters_list_item">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="filter_1_1">
                          <label class="form-check-label" for="filter_1_1">
                            Default checkbox Default checkbox
                          </label>
                        </div>
                      </li>
                      <li class="filters_list_item">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="filter_1_2">
                          <label class="form-check-label" for="filter_1_2">
                            Предотвратите разрушение макета ваших компонентов длинными строками текста.
                          </label>
                        </div>
                      </li>
                      <li class="filters_list_item">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="filter_1_3">
                          <label class="form-check-label" for="filter_1_3">
                            Default checkbox
                          </label>
                        </div>
                      </li>
                      <li class="filters_list_item">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="defaultCheck4">
                          <label class="form-check-label" for="defaultCheck4">
                            Default checkbox
                          </label>
                        </div>
                      </li>
                      <li class="filters_list_item">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="defaultCheck5">
                          <label class="form-check-label" for="defaultCheck5">
                            Default checkbox
                          </label>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>

              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#filter_3"
                      aria-expanded="false" aria-controls="filter_3">по цене
                  </button>
                </h2>
                <div id="filter_3" class="accordion-collapse collapse">
                    <!--data-bs-parent="#filters_accordion"-->
                  <div id="filter_price" class="accordion-body">
                    <div class="filter_price">
                      <input type="number" min="1000" max="100000"
                            value="1000" id="min_price" class="form-control" readonly>
                      <input type="number" min="1000" max="100000"
                            value="100000" id="max_price" class="form-control" readonly>
                    </div>
                    <div id="slider-range"></div>
                  </div>
                </div>
                <script>
                  let minPrice = 0;
                  let maxPrice = 100000;
                </script>
              </div>
            </div>
          </div><!--filters-->

          <div class="ukiyo_e">
            <img src="<?= base_url(POCKET_STYLE .'/assets/banner/sidebar-a.jpg'); ?>"
                alt="" loading="lazy"></img>
            <img src="<?= base_url(POCKET_STYLE .'/assets/banner/sidebar-b.jpeg'); ?>"
                alt="" loading="lazy"></img>
            <img src="<?= base_url(POCKET_STYLE .'/assets/banner/sidebar-c.jpeg'); ?>"
                alt="" loading="lazy"></img>  
          </div>
        </div><!--sidebar-->

        <div class="col-md-10 content">
          <div class="container content">
            <div id="category_content" class="row">

              <div class="col-lg-3 col-md-4 product-card" style="">
                <div class="product_expected">
                  <p>ожидается</p>
                </div>
                <a href="<?=base_url('/category/product');?>">
                  <div class="product-card-img">
                    <img src="<?= base_url('/images/for-face/11642.jpg'); ?>" alt="">
                  </div>
                </a>
                <div class="product-card-details">
                  <h6 class="product-card-title">
                    <a href="<?=base_url('/category/product');?>">
                      Эссенция против старения кожи с астаксантином Astaxanthin Aging Care Essence Re'senza
                    </a>
                  </h6>
                  <div class="product-card-price">
                    33 000р<del>35 000р</del>
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
            <!--/div--><!--row-->
          <!--/div--><!--container-->

              <div class="col-lg-3 col-md-4 product-card" style="">
                <div class="popular_product">
                  <p>рекомендуется</p>
                </div>
                <a href="#">
                  <div class="product-card-img">
                    <img src="<?= base_url('/images/aromatherapy/11383.png'); ?>" alt="">
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

              <div class="col-lg-3 col-md-4 product-card" style="">
                <a href="#">
                  <div class="product-card-img">
                    <img src="<?= base_url('/images/for-body/11870.jpeg'); ?>" alt="">
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

              <div class="col-lg-3 col-md-4 product-card" style="">
                <a href="#">
                  <div class="product-card-img">
                    <img src="<?= base_url('/images/for-body/11870.jpeg'); ?>" alt="">
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

              <div class="col-lg-3 col-md-4 product-card">
                <a href="#">
                  <div class="product-card-img">
                    <img src="<?= base_url('/images/for-body/11870.jpeg'); ?>" alt="">
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

              <div class="col-lg-3 col-md-4 product-card">
                <a href="#">
                  <div class="product-card-img">
                    <img src="<?= base_url('/images/for-body/11870.jpeg'); ?>" alt="">
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

              <div class="col-lg-3 col-md-4 product-card">
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
              </div>

              <div class="col-lg-3 col-md-4 product-card">
                <a href="#">
                  <div class="product-card-img">
                    <img src="<?= base_url('/images/no_image'); ?>" alt="">
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

              <!--div class="col-lg-3 col-md-4 product-card">
                <a href="#">
                  <div class="product-card-img">
                    <img src="<?= base_url('/images/for-body/11870.jpeg'); ?>" alt="">
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
              </div--><!--product-card-->
            </div>
          </div><!--row-->
          <nav aria-label="navigation">
            <ul class="pagination">
              <li class="page-item">
                <a class="page-link disabled" href="#" aria-label="Previous">
                  <span aria-hidden="true"><i class="fa-solid fa-chevron-left"></i></span>
                </a>
              </li>
              <li class="page-item"><a class="page-link active" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item">
                <a class="page-link" href="#" aria-label="Next">
                  <span aria-hidden="true"><i class="fa-solid fa-chevron-right"></i></span>
                </a>
              </li>
            </ul>
          </nav>
        </div><!--content-->
      </div><!--row category-->

    </div><!--container category-->
  </section>
  <script>localStorage.setItem('location', window.location.href);</script>

