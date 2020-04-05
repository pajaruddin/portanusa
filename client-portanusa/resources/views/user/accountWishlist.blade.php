@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
    <div class="container">
        <div class="account mt-5 mb-5">
            <div class="row">
                <div class="col-sm-3">
                    @include('user.navigation')
                </div>
                <div class="col-sm-9">
                    <div class="information">
                        <h3 class="font-weight-light">Wishlists</h3>
                        @include('product.listProduct')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin_scripts')
<script src="/plugins/jquery-validation/jquery.validate.min.js"></script>
@endpush

@push('custom_styles')
<link rel="stylesheet" href="/css/global.css">
<link rel="stylesheet" href="/css/product.css">
<link rel="stylesheet" href="/css/user.css">
@endpush

@push('custom_scripts')
<script src="/js/global.js"></script>
<script>
initFavs();
function initFavs() {
    var items = document.getElementsByClassName('product_fav');
    for (var x = 0; x < items.length; x++) {
        var item = items[x];
        item.addEventListener('click', function(fn) {
            fn.target.classList.toggle('active');
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
                }
            });
        });
    }
}
$(function(){

    jQuery.validator.addMethod("checkPassword", function(value, element){
        if (!/[A-Z]/.test(value)) {
            return false;
        } else if (!/[a-z]/.test(value)) {
            return false;
        } else if (!/[0-9]/.test(value)) {
            return false;
        } else if (/^[a-zA-Z0-9- ]*$/.test(value)){
            return false;
        }

        return true;
    }, "Error");

    $( "#register-form" ).validate( {
        rules: {
            password: "required",
            new_password: {
                required: true,
                minlength: 8,
                checkPassword: true
            },
            confirm_password: {
                required: true,
                equalTo: "#new_password"
            },
        },
        messages: {
            password: "Please enter your old password",
            new_password: {
                required: "Please enter your password",
                min: "Please enter at least 8 characters",
                checkPassword: "Password must contain capital letters, numbers, and special characters",
            },
            confirm_password: {
                required: "Please enter your confirm password",
                equalTo: "Confirm password and new password must match"
            }
        },
        errorElement: "h6",
        errorClass: "text-error",
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
    });

});
</script>
@endpush