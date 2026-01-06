/* скрыть поле поиска при scroll */
/*
$(window).scroll(function() {
    $('#search').hide();
    clearTimeout($.data(this, 'scrollTimer'));
    $.data(this, 'scrollTimer', setTimeout(function() {
        $('#search').show();
    }, 250));
});
*/

/*Спрятать кнопку поиска при scroll*/
$(window).scroll(function() {
    
    $("#search").css("display", "none").fadeIn("fast");

});

$(function() {

    /*спрятать кнопку #top */ 
    let btnTop = $('#top');
    $(window).scroll(function() {
        if ($(this).scrollTop() > 500) {
            $(btnTop).css("opacity", 1);
        
        } else {
            $(btnTop).css("opacity", 0);
        }
    });
    btnTop.click(function() {
        $('html, body').animate({scrollTop: 0}, 'slow');
        return false;
    });

    /*Развернуть форму поиска*/ 
    $('#button').on('click', function(e) {
        e.preventDefault();        
        let form = $(this).parent();
        let inputSearch = form.find('#input');
        inputSearch.toggleClass('hide').focus();

        if (inputSearch.val()) {
            form.submit();
        }

    });
    
    /* счётчик достижений */
    let counterBox = $('.achievements');
    if (counterBox.length) {
        let counterItem = $('.counter-num');
        let showCounter = true;

        $(window).on('scroll load resize', function () {
            let counterBoxTop = counterBox.offset().top;
            let windowHeight = window.innerHeight;
            let windowTop = $(window).scrollTop();

            if (showCounter && (counterBoxTop + 2 < windowTop + windowHeight)) {
                showCounter = false;
                counterItem.css('opacity', 1);
                counterItem.spincrement({
                    duration: 500,
                    fade: true
                });
            }
        });
    };

    /* =========Slider Promo========== */

    //$("#slider-promo, #slider-popular").owlCarousel({
    $("#slider-promo").owlCarousel({
        autoplay: true,
        loop: true,
        slideTransition: 'linear', // эффект бегущей строки (autoplayTimeout===autoplaySpeed)
        autoplayTimeout: 3000,    // пауза между переходами
        autoplaySpeed: 3000,     // скорость анимации
        smartSpeed: 1000,       // скорость при свайпе
        navSpeed: 1000,        // скорость при использовании стрелок
        dots: false,
		lazyLoad: true,
        mouseDrag: true,
        touchDrag: true,
        autoplayHoverPause: true,
        margin: 8,
        nav: false,
        responsive:{
            0: {
                items: 1
            },
            500: {
                items: 2
            },
            1000: {
                items: 3
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
    
    /* ==========Slider Popular========== */

    $("#slider-popular").owlCarousel({
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
        responsive:{
            0: {
                items: 1
            },
            500: {
                items: 2
            },
            1000: {
                items: 3
            },
            1400: {
                items: 4
            },
        }
    });
    
    /* ===========Slider Popular=========== */

    /* ===========Call Back================ */

    $("#phone").mask("+7(999)999-9999");

    /* ===========Call Back================ */

    /* видео owl carousel *//*
    
    $('#video.owl-carousel').owlCarousel({
        items: 1,
        loop: true,
        video: true,
        lazyLoad: true
    }); 
    */

});


/*
$(function () {

    let currentUri = location.origin + location.pathname.replace(/\/$/, '');
    $('.navbar-menu a').each(function () {
        let href = $(this).attr('href').replace(/\/$/, '');
        if (href === currentUri) {
            $(this).addClass('active');
        }
    });

    let iziModalAlertSuccess = $('.iziModal-alert-success');
    let iziModalAlertError = $('.iziModal-alert-error');

    iziModalAlertSuccess.iziModal({
        padding: 20,
        title: 'Success',
        headerColor: '#00897b'
    });
    iziModalAlertError.iziModal({
        padding: 20,
        title: 'Error',
        headerColor: '#e53935'
    });

    /*let form = document.querySelector('.ajax-form2');
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        let res = fetch('https://fr.loc/register', {
            method: 'post',
            body: new FormData(form),
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
            .then((response) => response.json())
            .then((data) => {
                console.log(data);
            });
    });*//*

    $('.ajax-form').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let btn = form.find('button');
        let btnText = btn.text();
        let method = form.attr('method');
        if (method) {
            method = method.toLowerCase();
        }
        let action = form.attr('action') ? form.attr('action') : location.href;

        $.ajax({
            url: action,
            type: method === 'post' ? 'post' : 'get',
            data: form.serialize(),
            beforeSend: function () {
                btn.prop('disabled', true).text('Отправляю...');
            },
            success: function (res) {
                res = JSON.parse(res);
                if (res.status === 'success') {
                    iziModalAlertSuccess.iziModal('setContent', {
                        content: res.data
                    });
                    form.trigger('reset');
                    iziModalAlertSuccess.iziModal('open');
                    if (res.redirect) {
                        $(document).on('closed', iziModalAlertSuccess, function (e) {
                            location = res.redirect;
                        });
                    }
                } else {
                    iziModalAlertError.iziModal('setContent', {
                        content: res.data
                    });
                    iziModalAlertError.iziModal('open');
                }
                btn.prop('disabled', false).text(btnText);
            },
            error: function () {
                alert('Error!');
                btn.prop('disabled', false).text(btnText);
            },
        });
    });
});
*/

