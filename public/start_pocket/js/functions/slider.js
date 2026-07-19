export function initSliders() {

  $("#slider-promo, #slider-popular").owlCarousel({
    autoplay: true,
    loop: true,
    //rewind: true,
    slideTransition: "linear",
    autoplayTimeout: 5000,
    autoplaySpeed: 5000,
    smartSpeed: 1000,
    navSpeed: 1000,
    lazyLoad: false,
    mouseDrag: true,
    touchDrag: true,
    autoplayHoverPause: true,
    nav: false,
    dots: false,
    responsive: {
      0: { items: 1, margin: 5 },
      500: { items: 2, margin: 10 },
      1000: { items: 3, margin: 15 },
      1400: { items: 4 }
    }
  });

  $(".prev-btn").off("click.initSliderPromo").on("click.initSliderPromo", function () {
    $(".owl-carousel").trigger("prev.owl.carousel");
  });

  $(".next-btn").off("click.initSliderPromo").on("click.initSliderPromo", function () {
    $(".owl-carousel").trigger("next.owl.carousel");
  });

  $("#slider-product").owlCarousel({
    autoplay: true,
    loop: true,
    slideTransition: "ease",
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
    responsive: {
      0: { items: 1, margin: 5 },
      500: { items: 2, margin: 10 },
      1000: { items: 3, margin: 15 },
      1400: { items: 4 }
    }
  });
  
}

