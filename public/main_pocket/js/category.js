$(document).ready(function() {

    /****** выбор ценового диапазона *******/
    const sliderRange = $('#slider-range');

    if( sliderRange ) {
        let minPriceInput = $('#min_price');
        let maxPriceInput = $('#max_price');

        $( "#slider-range" ).slider({
            range: true,
            min: minPrice,
            max: maxPrice,
            values: [ minPrice, maxPrice ],
            slide: function( event, ui ) {
                minPriceInput.val(ui.values[0]);
                maxPriceInput.val(ui.values[1]);
            }
        });
    }


    /******* скрыть гравюры и показать кнопку *******/

    // глобальная переменная для хранения высоты блока фильтров
    window.filters_block_height = 10;
    // получение и сохранение высоты
    function update_block_height() {
        // .height() - без padding/border, .outerHeight(true) - с margin/padding/border
        //window.filters_block_height += $('.filters').height();
        window.filters_block_height += $('.filters').outerHeight(true);
    
    }
    // вызываем при загрузке страницы
    $(function() {
        update_block_height();
    
    });

    const filters        = $('.filters');
    const btn_filters    = document.getElementById('btn_activate_filters');
    const selector_link  = btn_filters.querySelector('#selector_link');
    const selector_text  = btn_filters.querySelector('#selector_text');          
    const block_filters  = $('.filters');           
    const block_ukiyo_e  = $('.ukiyo_e');

    const activate_filter = new ResizeObserver(entries => {
        for ( let entry of entries ) {
            const { width, height } = entry.contentRect;
            if ( block_filters.height() > filters_block_height ) {
                block_ukiyo_e.fadeOut(400); //hide();
                selector_link.setAttribute('href', 'http://localhost:8888');
                selector_link.setAttribute('class', 'btn btn-outline-primary activate_filters');
                selector_text.setAttribute('class', 'activate_filters');
                selector_text.textContent = 'применить фильтры';

            } else {
                block_ukiyo_e.fadeIn(400); //show();
                selector_link.setAttribute('class', '');
                selector_text.setAttribute('class', '');
                selector_text.textContent = 'Фильтры';
            
            }
        }
    });
    activate_filter.observe(filters[0]);


    /* одиночные карточки */       
    /*
    var cards_array = [];
    $('.product-card').each(function(index) {
        var value = index;
        cards_array.push(value);

        if ( cards_array.length < 3 ) {
            var i = 3;
            while ( i > cards_array.length ) {
                //console.log('test-');
                $('.product-card').attr('class', 'col-lg-3 col-md-4 product-card');
                $('.product-card').attr('style', 'width:31%;');
                i-- ;   
            }
        }
    });
    */
    

    /* скрыть гравюры *//*
    const sidebar = $('.sidebar');
    const ukiyo_e  = $('.ukiyo_e');
    const sidebar_height  = 1450;

    const hide_ukiyo_e = new ResizeObserver(entries => {
        for ( let entry of entries ) {
            const { width, height } = entry.contentRect;
            if ( sidebar.height() < sidebar_height ) {
                ukiyo_e.hide();

            } else {
                ukiyo_e.show();

            }
        }
    });
    hide_ukiyo_e.observe(element[0]);
    */


});
