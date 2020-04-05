<script>
function filterStockStatus(){
    var currentUrl = "{{ url()->current() }}";

    $.ajax({
        url: "{{url('/product/list/filter/stock-status')}}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {currentUrl: currentUrl},
        type: 'POST',
        dataType: 'json',
        success: function (result) {
            if (result.status == "success") {
              $('.product-view').html(result.html);
              $('#filterStockStatus').toggleClass('active');
            }
        }
    });
}
function filterStatus(){
    var currentUrl = "{{ url()->current() }}";

    $.ajax({
        url: "{{url('/product/list/filter/status')}}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {currentUrl: currentUrl},
        type: 'POST',
        dataType: 'json',
        success: function (result) {
            if (result.status == "success") {
              $('.product-view').html(result.html);
              $('#filterStatus').toggleClass('active');
            }
        }
    });
}
function sortProduct(orderByVal){
    var orderBy = orderByVal;
    var label = $('.'+orderByVal).html();
    var currentUrl = "{{ url()->current() }}";
    $.ajax({
        url: "{{url('/product/list/filter/sort-product')}}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {orderBy: orderBy, currentUrl: currentUrl},
        type: 'POST',
        dataType: 'json',
        success: function (result) {
            if (result.status == "success") {
              $('.product-view').html(result.html);
              $('.sorting_text').html(label + '<i class="fas fa-chevron-down"></i>');
            }
        }
    });
}
function filterPrice(){
    var price = $('#price').val();
    var currentUrl = "{{ url()->current() }}";
    $.ajax({
        url: "{{url('/product/list/filter/price')}}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {price: price, currentUrl: currentUrl},
        type: 'POST',
        dataType: 'json',
        success: function (result) {
            if (result.status == "success") {
                $('.product-view').html(result.html);
            }
        }
    });
}
function initFavs() {
    var items = document.getElementsByClassName('product_fav');
    for (var x = 0; x < items.length; x++) {
        var item = items[x];
        item.addEventListener('click', function(fn) {
            var productId  = $(this).attr("data-productId");
            $.ajax({
                url: "{{url('/product/wishlist')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {productId: productId},
                type: 'POST',
                dataType: 'json',
                success: function (result) {
                    new Noty({
                        type: result.status,
                        text: result.message,
                        layout: 'center',
                        timeout: 2000,
                        modal: true
                    }).show();
                    fn.target.classList.toggle('active');
                }
            });
        });
    }
}
</script>
