function showBSCoreToast(detailMessage, type = 'success') {

    const container = document.querySelector('.toast-container');
    const template = document.getElementById('toast-template');

    if (!container || !template) return;

    const clone = template.content.cloneNode(true);
    const toastEl = clone.querySelector('.toast');
    const iconEl = clone.querySelector('.jp-icon');
    const titleEl = clone.querySelector('.jp-status-label');
    const textEl = clone.querySelector('.jp-detail-text');

    // Настройка темы оформления
    if (type === 'success') {
        toastEl.classList.add('bg-success');
        iconEl.textContent = '済'; // завершено
        titleEl.textContent = 'Добавлено в корзину';
    } else {
        toastEl.classList.add('bg-danger');
        iconEl.textContent = '誤'; // ошибка
        titleEl.textContent = 'Товар не найден';
    }
    textEl.textContent = detailMessage;

    container.appendChild(clone);

    const bsToast = new bootstrap.Toast(toastEl, {
        autohide: true,
        delay: 4000
    });

    toastEl.addEventListener('hide.bs.toast', () => {
        toastEl.style.pointerEvents = 'none';
    });

    // Событие 'hidden.bs.toast' срабатывает, когда Bootstrap закончил свою анимацию.
    toastEl.addEventListener('hidden.bs.toast', () => {
        // Безопасное и плавное скрытие без конфликта с CSS Bootstrap
        toastEl.style.maxHeight = toastEl.offsetHeight + 'px';

        // Форсируем перерисовку (Reflow) для запуска анимации
        toastEl.offsetHeight;

        // Предотвращаем резкий прыжок: плавно убираем высоту и отступы
        //toastEl.style.transition = 'max-height 0.3s ease, opacity 0.3s ease, margin 0.3s ease, padding 0.3s ease';
        toastEl.style.transition = 'all 0.4s ease-in-out';
        toastEl.style.maxHeight = '0';
        toastEl.style.opacity = '0';
        toastEl.style.marginTop = '0';
        toastEl.style.marginBottom = '0';
        toastEl.style.paddingTop = '0';
        toastEl.style.paddingBottom = '0';
        toastEl.style.overflow = 'hidden';

        // Удаляем физически из DOM только после завершения "схлопывания"
        setTimeout(() => {
            toastEl.remove();
        }, 300);
    });

    bsToast.show();
}

$(document).ready(function() {
    // Используем делегирование событий на случай динамической подгрузки товаров
    $(document).on('click', '.add-to-cart', function (e) {
        e.preventDefault();

        //const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const btn = $(this);
        const icon = btn.find('i');
        const loader = btn.find('div');
        const productId = btn.data('id');

        $.ajax({
            url: baseUrl + 'add-to-cart',
            method: 'GET',
            data: { 'id': productId },
            beforeSend: function () {
                btn.prop('disabled', true);
                icon.addClass('d-none');
                loader.removeClass('d-none');
            },
            success: function (res) {
                showBSCoreToast(res.data || 'Успешно добавлено', 'success');

                // Поиск по контексту текущей карточки товара надежнее, чем поиск по всему DOM
                //btn.removeClass('btn-outline-secondary').addClass('btn-outline-primary');
                
                $('#offcanvasCart .offcanvas-body').html(res.mini_cart);
                $('.offcanvas-cart-qty').text(res.cart_qty);
            },
            error: function (request) {
                let errorMsg = request.responseText || 'Произошла ошибка';
                showBSCoreToast(errorMsg, 'danger');
            },
            complete: function () {
                setTimeout(() => {
                    btn.prop('disabled', false);
                    icon.removeClass('d-none');
                    loader.addClass('d-none');
                }, 500);
            },
        });
    });
//});

 


    /*
    let addToCart = $('.add-to-cart');
    // remove from cart
    $('body').on('click', '.btn-cart-remove', function (e) {
        e.preventDefault();
        let btn = $(this);
        let icon = btn.find('i');
        let loader = btn.find('div');
        let productId = $(this).data('id');

        $.ajax({
            url: baseUrl + 'remove-from-cart',
            method: 'GET',
            data: {
                'id': productId
            },
            beforeSend: function () {
                $('.btn-cart-remove').prop('disabled', true);
                icon.addClass('d-none');
                loader.removeClass('d-none');
            },
            success: function (res) {
                toastr.success(res.data);
                $('.product-id-' + productId).find('.add-to-cart').
                    removeClass('btn-outline-primary').addClass('btn-outline-secondary');
                $('#offcanvasCart .offcanvas-body').html(res.mini_cart);
                $('.offcanvas-cart-qty').text(res.cart_qty);
            },
            error: function (request) {
                toastr.error(request.responseText);
                btnCartRemove.prop('disabled', false);
                icon.removeClass('d-none');
                loader.addClass('d-none');
            }
        });
    });

    // add to cart
    
    addToCart.on('click', function (e) {
        e.preventDefault();
        let btn = $(this);
        let icon = btn.find('i');
        let loader = btn.find('div');
        let productId = $(this).data('id');

        $.ajax({
            url: baseUrl + 'add-to-cart',
            method: 'GET',
            data: {
                'id': productId
            },
            beforeSend: function () {
                // btn.prop('disabled', true);
                addToCart.prop('disabled', true);
                icon.addClass('d-none');
                loader.removeClass('d-none');
            },
            success: function (res) {
                toastr.success(res.data);
                $('.product-id-' + productId).find('.add-to-cart').
                    removeClass('btn-outline-secondary').addClass('btn-outline-primary');
                $('#offcanvasCart .offcanvas-body').html(res.mini_cart);
                $('.offcanvas-cart-qty').text(res.cart_qty);
            },
            error: function (request) {
                toastr.error(request.responseText);
            },
            complete: function () {
                setTimeout(() => {
                    // btn.prop('disabled', false);
                    addToCart.prop('disabled', false);
                    icon.removeClass('d-none');
                    loader.addClass('d-none');
                    productId = undefined;
                }, 500);
            },
        });
    });

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "500",
        "timeOut": "4000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "slideDown",
        "hideMethod": "slideUp"
    }
    */

       /* 
    // add-to-cart 
    $('.add-to-cart').on('click', function(e) {
        e.preventDefault(); // отправить данные без перезагрузки страницы
    //$('.add-to-cart').on('click', function() {
        let btn = $(this);
        let productId = $(this).data('id');
        let icon = btn.find('i');
        let loader = btn.find('div');

        $.ajax({
            url: baseUrl + 'add-to-cart',
            metod: 'GET',
            data: {
                'id': productId
            },
            beforeSend: function() {
                btn.prop('disabled', true);
                icon.addClass('d-none');
                loader.removeClass('d-none');
            },
            success: function (result) {
                
                console.log(result);
            },
            error: function(request) {
                
                console.log(request);
            },
            complete: function() {
                btn.prop('disabled', false);
                icon.removeClass('d-none');
                loader.addClass('d-none');
            }
        });
    
    });*/
    //---------------------------------------
    

    /*
    setTimeout(function() {
        var target = $('#new_top');

        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top
            }, {
                duration: 1850, // Увеличим время для более заметного эффекта
                easing: 'swing', // Стандартная плавная остановка
                complete: function() {
                        //console.log('Прокрутка завершена точно у цели');
                }
            });
        }
    }, 5000);*/
    
    //
    // new_start
    //
      /* 
    $(function() {
        var key = 'scrolled_to_new_start';
        if (sessionStorage.getItem(key)) return;

        var idleTimeout = 5000; // N секунд
        var idleTimer;

        // селектор поля поиска — поправьте при необходимости
        var searchSelector = 'input[type="search"], input.search, #search, .search input';

        function markDone() {
            sessionStorage.setItem(key, '1');
            clearTimeout(idleTimer);
        }

        function startIdleTimer() {
            clearTimeout(idleTimer);
            idleTimer = setTimeout(function() {
            var target = $('#new_top');
            if (target.length) {
                $('html, body').animate({ scrollTop: target.offset().top }, 1850, 'swing', markDone);
            } else {
                markDone();
            }
            }, idleTimeout);
        }

        // События, которые сбрасывают таймер (т.е. активность)
        $(document).on('mousemove click keydown scroll', function(e) {
            // Для keydown допускаем только ввод в поле поиска
            // — иначе любое нажатие клавиш будет считать активностью.
            if (e.type === 'keydown') {
            // если фокус в поле поиска или событие произошло в элементе поиска — считаем активностью
            if ($(e.target).is(searchSelector) || $(e.target).closest(searchSelector).length) {
                startIdleTimer();
            }
            // иначе игнорируем keydown как активность
            } else {
            // mousemove, click, scroll — считаем активностью
            startIdleTimer();
            }
        });

        // Также слушаем ввод в поле поиска (input) — активность
        $(document).on('input', searchSelector, startIdleTimer);

        // Запускаем первый таймер при загрузке
        startIdleTimer();

        // Если прокрутка была вызвана вручную до таймера — пометим как выполненное
        $(window).one('scroll', function() {
            // если пользователь сам прокрутил к нужной позиции, не выполнять авто-прокрутку
            var target = $('#new_top');
            if (target.length) {
            var top = target.offset().top;
            var st = $(window).scrollTop();
            if (Math.abs(st - top) < 50) {
                markDone();
            }
            }
        });
    });
    */
    //---------------------------------------
    
    // получить кнопку "вернуться наверх"
    //
    top_btn = document.getElementById("top_btn");
    window.onscroll = function() { scrollFunction() };
    function scrollFunction() {
        if (document.body.scrollTop > 1000 || document.documentElement.scrollTop > 1000) {
            top_btn.style.display = "block";
        } else {
            top_btn.style.display = "none";
        }
    }
    document.getElementById("top_btn").addEventListener("click", function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    
    });
    //----------------------------------------
    /*
    // развернуть форму поиска 
    //
    $('#submit').on('click', function(e) {
        e.preventDefault();        
        let form = $(this).parent();
        let inputSearch = form.find('#input');
        inputSearch.toggleClass('hide').focus();

        if (inputSearch.val()) {
            form.submit();
        }

    });
    //-----------------------------------------
    //
    // спрятать кнопку поиска при scroll
    //
    $(window).scroll(function() {
        $("#search").css("display", "none").fadeIn("fast");

    });
    
    /* 
    // счётчик достижений
    let counterBox = $('.achievements');
    if (counterBox.length) {
        let counterItem = $('.counter-num');
        let showCounter = true;

        $(window).on('scroll load resize', function () {
            let counterBoxTop = counterBox.offset().top;
            let windowHeight = window.innerHeight;
            let windowTop = $(window).scrollTop();
            
            if (showCounter && (counterBoxTop + 1 < windowTop + windowHeight)) {
                showCounter = false;
                counterItem.css('opacity', 1);
                counterItem.spincrement({
                    duration: 2500,
                    fade: true
                });
            }
        });
    };
    

    /*
    // счётчик достижений
    let counterBox = $('.achievements');

    if (counterBox.length) {
        let counterItem = $('.counter-num');

        // Запускаем таймер через 1000 мс (1 секунда) после загрузки DOM
        $(document).ready(function() {
            setTimeout(function() {
                counterItem.css('opacity', 1);
                counterItem.spincrement({
                    duration: 2500,
                    fade: true
                });
            }, 1000); 
        });
    }
    */
    /*
    let counterItem = $('.counter-num');

    if (counterItem.length) {
        $(document).ready(function() {
            // Общая задержка перед стартом первой цифры (1 секунда)
            const baseDelay = 1000; 
            // Интервал между появлением каждой следующей цифры (например, 0.3 сек)
            const step = 300; 

            counterItem.each(function(index) {
                let $this = $(this);
                
                setTimeout(function() {
                    $this.css('opacity', 1);
                    $this.spincrement({
                        duration: 2000, // длительность самой анимации цифр
                        fade: true
                    });
                }, baseDelay + (index * step)); 
                // Для 1-го элемента: 1000 + 0 = 1000мс
                // Для 2-го элемента: 1000 + 300 = 1300мс
                // Для 3-го элемента: 1000 + 600 = 1600мс и т.д.
            });
        });
    }
    */ 
    /*
    let achievementItems = $('.achievement-item');

    if (achievementItems.length) {
        const baseDelay = 500; // Уменьшил задержку для теста
        const step = 400;

        achievementItems.each(function(index) {
            let $item = $(this);
            let $num = $item.find('.counter-num');
            
            // Очищаем текст от лишних символов, оставляя только цифры
            let finalValue = parseInt($num.text().replace(/\s/g, '')) || 0;

            setTimeout(function() {
                $item.addClass('visible');
                
                // Проверяем, существует ли функция в системе
                if ($.fn.spincrement) {
                    $num.spincrement({
                        from: 0,
                        to: finalValue,
                        duration: 9000,
                        thousandSeparator: ' ', // Добавит пробел в больших числах (3 150)
                        fade: false
                    });
                } else {
                    console.error("Плагин Spincrement не подключен!");
                    $num.text(finalValue); // Просто выводим число, если плагина нет
                }
            }, baseDelay + (index * step));
        });
    }    
    */
    /*     
    let achievementItems = $('.achievement-item');
    
    if (achievementItems.length) {
        const baseDelay = 2000; 
        const step = 400;
            
            // Ваш порядок: сначала последний (индекс 2), потом средний (1), потом первый (0)
        let appearanceOrder = [achievementItems.length - 1, 1, 0];
    
        appearanceOrder.forEach(function(itemIndex, orderIndex) {
            let $item = $(achievementItems[itemIndex]);
            if (!$item.length) return;
    
        let $num = $item.find('.counter-num');
        let finalValue = parseInt($num.text().replace(/\s/g, '')) || 0;
    
            setTimeout(function() {
                    // СИНХРОННЫЙ СТАРТ:
                    // Добавляем класс — в этот же миг в CSS срабатывает opacity блока и width линии
                $item.addClass('visible');
                $num.text('0');
    
                    // Запуск цифр чуть позже, когда линия уже начала движение
                setTimeout(function() {
                    if ($.fn.spincrement) {
                        $num.spincrement({
                        from: 0,
                        to: finalValue,
                        duration: 1000, // Чуть ускорил для динамики
                        thousandSeparator: ' ',
                        fade: false
                    });
                    } else {
                        $num.text(finalValue);
                    }
                }, 400); // Пауза перед цифрами (согласуется с началом анимации линии)
    
            }, baseDelay + (orderIndex * step)); 
        });
    }
    */

    /* достижения */
    
    let achievementItems = $('.achievement-item');

    if (achievementItems.length) {
        const baseDelay = 400; 
        const step = 200;
        
        // Порядок: последний (2), средний (1), первый (0)
        let appearanceOrder = [achievementItems.length - 1, 1, 0];

        appearanceOrder.forEach(function(itemIndex, orderIndex) {
            let $item = $(achievementItems[itemIndex]);
            if (!$item.length) return;

            setTimeout(function() {
                // Просто делаем блок видимым. 
                // Финальное число уже есть в HTML, оно просто отобразится вместе с блоком.
                $item.addClass('visible');
            }, baseDelay + (orderIndex * step)); 
        });
    }
    
    
    
    /* попробовать */
    /*
    let achievementItems = $('.achievement-item');

    if (achievementItems.length) {
        const baseDelay = 2000; 
        const step = 400;
        
        let appearanceOrder = [achievementItems.length - 1, 1, 0];

        appearanceOrder.forEach(function(itemIndex, orderIndex) {
            let $item = $(achievementItems[itemIndex]);
            if (!$item.length) return;

            let $num = $item.find('.counter-num');
            let $desc = $item.find('.achievement-description'); // Класс вашего описания
            let finalValue = parseInt($num.text().replace(/\s/g, '')) || 0;

            setTimeout(function() {
                $item.addClass('visible');
                $num.text('0');

                setTimeout(function() {
                    if ($.fn.spincrement) {
                        $num.spincrement({
                            from: 0,
                            to: finalValue,
                            duration: 1000,
                            thousandSeparator: ' ',
                            // ФУНКЦИЯ ПОСЛЕ ЗАВЕРШЕНИЯ СЧЕТА:
                            complete: function() {
                                $desc.addClass('show-description'); 
                            }
                        });
                    } else {
                        $num.text(finalValue);
                        $desc.addClass('show-description');
                    }
                }, 400);

            }, baseDelay + (orderIndex * step)); 
        });
    }
    */


    /* =========Slider Promo========== */

    
    $("#slider-promo").owlCarousel({
        autoplay: false, //true,
        loop: false, //true,
        slideTransition: 'linear', // эффект бегущей строки (autoplayTimeout===autoplaySpeed)
        autoplayTimeout: 3000,    // пауза между переходами
        autoplaySpeed: 3000,     // скорость анимации
        smartSpeed: 1000,       // скорость при свайпе
        navSpeed: 1000,        // скорость при использовании стрелок
		lazyLoad: true,
        mouseDrag: true,
        touchDrag: true,
        autoplayHoverPause: true,
        //margin: 8,
        nav: false,
        dots: false,
        responsive:{
            0: {
                items: 1,
                margin: 5 // Отступ для мобильных
            },
            500: {
                items: 2,
                 margin: 10 // МЕНЬШИЙ отступ для планшетов
            },
            1000: {
                items: 3,
                margin: 15 // Отступ для десктопа
            },
            1400: {
                items: 4
            },
        }
    });

    /* =============Slider Promo================ */
    /* ===========кнопки управления============= */

    $('.prev-btn').click(function() {
        $('.owl-carousel').trigger('prev.owl.carousel');
    
    });
    $('.next-btn').click(function() {
        $('.owl-carousel').trigger('next.owl.carousel');
    
    });
    
    /* ========== Slider-Popular & Slider-Product ========== */

    $("#slider-popular, #slider-product").owlCarousel({
        autoplay: false, //true,
        loop: false, //true,
        autoplayTimeout: 5000,
        autoplaySpeed: 3000,
        smartSpeed: 1000,
        navSpeed: 1000,
        lazyLoad: true,
        mouseDrag: true,
        touchDrag: true,
        autoplayHoverPause: true,
        margin: 8,
        nav: false,
        dots: false,
        responsive:{
            0: {
                items: 1,
                margin: 5
            },
            500: {
                items: 2,
                margin: 10
            },
            1000: {
                items: 3,
                margin: 15
            },
            1400: {
                items: 4
            },
        }
    });
    
    /* ===========Slider Popular=========== */

    /* =========== Call Back ================ */

    $("#phone").mask("+7(999)999-9999");

    /* =========== Call Back ================ */

    /* видео owl carousel *//*
    
    $('#video.owl-carousel').owlCarousel({
        items: 1,
        loop: true,
        video: true,
        lazyLoad: true
    }); 
    */
   
    /*
    var counter = 0;
    window.onblur = function(event) {
        counter++;
        console.log('Пользователь покинул вкладку: ' + counter);
    };*/


    /* =========== tooltip =========== */

    $(function() {
        $(".user_out").tooltip({ placement: 'left' });
        
    });


    /* =========== enter =========== */

    /*
    $('#login_button').click(function() {
        $('.navbar-icon').load(window.location.href + ' .navbar-icon');
    });*/


    /* ============ рейтинг ============ */
    
    /* ============ cart =============== */


    /* ---------- тёмная тема ----------- */
    /*
    const btn = document.getElementById("theme-toggle");
    const moon = btn?.querySelector('.icon-moon');
    const stored = localStorage.getItem("theme");
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    let theme = stored || (prefersDark ? 'dark' : 'light');

    // Применяем тему
    document.documentElement.setAttribute('data-theme', theme);
    if (btn) btn.setAttribute('aria-pressed', theme === 'dark');
    if (moon) moon.setAttribute('aria-hidden', theme === 'dark' ? 'false' : 'false'); // оставляем доступность

    btn?.addEventListener('click', () => {
    theme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    if (btn) btn.setAttribute('aria-pressed', theme === 'dark');
    });
    */
    const btn = document.getElementById("theme-toggle");
    const moon = btn?.querySelector('.icon-moon');
    const stored = localStorage.getItem("theme");
    // игнорируем prefers-color-scheme, если нет сохранённой темы
    let theme = stored || 'light';

    // Применяем тему
    document.documentElement.setAttribute('data-theme', theme);
    if (btn) btn.setAttribute('aria-pressed', theme === 'dark');
    if (moon) moon.setAttribute('aria-hidden', theme === 'dark' ? 'false' : 'false');

    // Переключение кнопкой
    btn?.addEventListener('click', () => {
    theme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    if (btn) btn.setAttribute('aria-pressed', theme === 'dark');
    });




    /* =========== ссылка на сайт при копировании =========== */
    /*
    function wpguruLink() {
        var istS = 'Источник:'; // Слово должно находится в кавычках!
        //var copyR = '© japan-in.ru'; // Слово должно находится в кавычках!
        var body_element = document.getElementsByTagName('body')[0];
        var choose = window.getSelection();
        var myLink = document.location.href;
        var authorLink = "<br /><br />" + istS + ' ' + "<a href='"+myLink+"'>"+myLink+"</a><br />"; // + copyR;
        var copytext = choose + authorLink;
        var addDiv = document.createElement('div');
        addDiv.style.position='absolute';
        addDiv.style.left='-99999px';
        body_element.appendChild(addDiv);
        addDiv.innerHTML = copytext;
        choose.selectAllChildren(addDiv);
        window.setTimeout(function() {
            body_element.removeChild(addDiv);
        },0);
    }
    document.oncopy = wpguruLink;
    */

    /*
    toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-bottom-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "500",
    "timeOut": "4000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "slideDown",
    "hideMethod": "slideUp"
    }*/

});
