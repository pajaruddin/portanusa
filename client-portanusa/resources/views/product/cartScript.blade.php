@if(Auth::check())
<script>
    function addToCart(){
        var product_id = $('.product-id').val();
        $.ajax({
            url: "{{url('/cart/add')}}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {product_id: product_id},
            type: 'POST',
            dataType: 'json',
            success: function (result) {
                var n = new Noty({
                    type: 'alert',
                    text: 'Product successfully saved in cart',
                    layout: 'center',
                    timeout: 2000,
                    modal: true,
                    buttons: [
                        Noty.button('Pay now', 'btn btn-success btn-sm', function () {
                            window.location.href = "{{ AppConfiguration::primaryDomain()->value }}/cart";
                        }),

                        Noty.button('Continue shopping', 'ml-1 btn btn-danger btn-sm', function () {
                            n.close();
                        })
                    ]
                });
                n.show();

                $('.cart_total').html("<span>"+result.totalCart+"</span>");
            }
        });
    }
</script>
@else
<script>
    function addToCart(){
        showLoginModal();
    }
</script>
@endif