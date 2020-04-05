$(document).ready(function() {
    "use strict";

    /* 

    1. Vars and Inits

    */

    initViewedSlider();
    initBrandsSlider();
    initIsotope();
    initPriceSlider();
    initFavs();

    /* 

    5. Init Recently Viewed Slider

    */

    function initViewedSlider() {
        if ($('.viewed_slider').length) {
            var viewedSlider = $('.viewed_slider');

            viewedSlider.owlCarousel({
                loop: true,
                margin: 30,
                autoplay: true,
                autoplayTimeout: 6000,
                nav: false,
                dots: false,
                responsive: {
                    0: { items: 1 },
                    575: { items: 2 },
                    768: { items: 3 },
                    991: { items: 4 },
                    1199: { items: 6 }
                }
            });

            if ($('.viewed_prev').length) {
                var prev = $('.viewed_prev');
                prev.on('click', function() {
                    viewedSlider.trigger('prev.owl.carousel');
                });
            }

            if ($('.viewed_next').length) {
                var next = $('.viewed_next');
                next.on('click', function() {
                    viewedSlider.trigger('next.owl.carousel');
                });
            }
        }
    }

    /* 

    6. Init Brands Slider

    */

    function initBrandsSlider() {
        if ($('.brands_slider').length) {
            var brandsSlider = $('.brands_slider');

            brandsSlider.owlCarousel({
                loop: true,
                autoplay: true,
                autoplayTimeout: 5000,
                nav: false,
                dots: false,
                autoWidth: true,
                items: 8,
                margin: 42
            });

            if ($('.brands_prev').length) {
                var prev = $('.brands_prev');
                prev.on('click', function() {
                    brandsSlider.trigger('prev.owl.carousel');
                });
            }

            if ($('.brands_next').length) {
                var next = $('.brands_next');
                next.on('click', function() {
                    brandsSlider.trigger('next.owl.carousel');
                });
            }
        }
    }

    /* 

    7. Init Isotope

    */

    function initIsotope() {
        var sortingButtons = $('.shop_sorting_button');

        $('.product_grid').isotope({
            itemSelector: '.product_item',
            getSortData: {
                price: function(itemElement) {
                    var priceEle = $(itemElement).find('.product_price').text().replace("Rp ", "");
                    return parseFloat(priceEle);
                },
                name: '.product_name div a'
            },
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            }
        });

        // Sort based on the value from the sorting_type dropdown
        sortingButtons.each(function() {
            $(this).on('click', function() {
                $('.sorting_text').text($(this).text());
                var option = $(this).attr('data-isotope-option');
                option = JSON.parse(option);
                $('.product_grid').isotope(option);
            });
        });

    }

    /* 

	8. Init Price Slider

	*/

    function initPriceSlider() {
        if ($("#slider-range").length) {
            $("#slider-range").slider({
                range: true,
                min: 0,
                max: 10000000,
                values: [0, 5000000],
                slide: function(event, ui) {
                    $("#amount").val("Rp" + ui.values[0] + " - Rp" + ui.values[1]);
                }
            });

            $("#amount").val("Rp" + $("#slider-range").slider("values", 0) + " - Rp" + $("#slider-range").slider("values", 1));
            $('.ui-slider-handle').on('mouseup', function() {
                $('.product_grid').isotope({
                    filter: function() {
                        var priceRange = $('#amount').val();
                        var priceMin = parseFloat(priceRange.split('-')[0].replace('Rp', ''));
                        var priceMax = parseFloat(priceRange.split('-')[1].replace('Rp', ''));
                        var itemPrice = $(this).find('.product_price').clone().children().remove().end().text().replace("Rp ", "");
                        alert(itemPrice);
                        return (itemPrice > priceMin) && (itemPrice < priceMax);
                    },
                    animationOptions: {
                        duration: 750,
                        easing: 'linear',
                        queue: false
                    }
                });
            });
        }
    }

    /* 

	9. Init Favorites

	*/

    function initFavs() {
        // Handle Favorites
        var items = document.getElementsByClassName('product_fav');
        for (var x = 0; x < items.length; x++) {
            var item = items[x];
            item.addEventListener('click', function(fn) {
                fn.target.classList.toggle('active');
            });
        }
    }
});