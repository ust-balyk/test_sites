$(document).ready(function() {

    
    // получить кнопку "вернуться наверх"
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


    // развернуть форму поиска 
    
    $('#submit').on('click', function(e) {
        e.preventDefault();        
        let form = $(this).parent();
        let inputSearch = form.find('#input');
        inputSearch.toggleClass('hide').focus();

        if (inputSearch.val()) {
            form.submit();
        }

    });
    // спрятать кнопку поиска при scroll
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
        autoplay: true,
        loop: true,
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
        autoplay: true,
        loop: true,
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
        $(".add-to-favorites").tooltip();
        $(".add-to-cart").tooltip();
        $(".user_out").tooltip();
    });


    /* =========== enter =========== */

    /*
    $('#login_button').click(function() {
        $('.navbar-icon').load(window.location.href + ' .navbar-icon');
    });*/


    /* ============ рейтинг ============ */
    
    /* ============ cart =============== */

    // Переключение видимости корзины
    function toggleCart() {
        const modal = document.getElementById('cart-modal');
        modal.style.display = (modal.style.display === 'none' || modal.style.display === '') ? 'block' : 'none';
        renderUI(); // Обновляем данные при открытии
    }

    // Закрытие при клике вне окна
    window.onclick = function(event) {
        const modal = document.getElementById('cart-modal');
        if (event.target == modal) toggleCart();
    }

    // Обновим renderUI, чтобы он также обновлял счетчик в хедере
    function renderUI() {
        const cartItemsWrapper = document.getElementById('cart-items');
        const cartTotalEl = document.getElementById('cart-total');
        const cartCountEl = document.getElementById('cart-count'); // Тот, что в хедере

        // Считаем общее количество товаров для иконки
        const totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCountEl.innerText = totalQty;

        cartItemsWrapper.innerHTML = '';
        let total = 0;

        if (cart.length === 0) {
            cartItemsWrapper.innerHTML = '<p style="text-align:center; color:#999;">Корзина пуста</p>';
        } else {
            cart.forEach(item => {
                total += item.price * item.quantity;
                cartItemsWrapper.innerHTML += `
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #eee;">
                        <div>
                            <div style="font-weight:bold;">${item.name}</div>
                            <div style="font-size:0.9em; color:#666;">${item.price} руб.</div>
                        </div>
                        <div style="display: flex; align-items: center;">
                            <button onclick="removeFromCart(${item.id})" style="width:25px;">-</button>
                            <span style="margin: 0 10px;">${item.quantity}</span>
                            <button onclick="addToCart(${item.id}, '${item.name}', ${item.price})" style="width:25px;">+</button>
                        </div>
                    </div>
                `;
            });
        }
        cartTotalEl.innerText = total;
    }


    
    /* =========== ссылка на сайт при копировании =========== */

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

});
