@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
<?php $total_price = 0; ?>
@if(count($products_cart) != 0)
<div class="cart mt-5 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mb-4">
                <h4 class="text-header mb-4">
                    <i class="fa fa-shopping-cart"></i> {{ DisplayCart::countCart() }} Product in cart
                    {{-- <a href="javascript:;" class="float-right">{{ trans('form.text_delete_some') }}</a> --}}
                </h4>
                @foreach($products_cart as $product)
                <?php
                $price = DisplayProductPrice::getPrice($product->id);
                $product_discount = DisplayProductPrice::getDiscount($product->id, $price);

                $price_after_discount = $product_discount['price'];

                $product_status = DisplayProductCart::getStatus($product->id, $price);
                ?>
                <div class="content-cart mb-3 product-list" id="product-{{ $product->id }}">
                    <div class="row">
                        <div class="col-md-2 col-4">
                            <img src="{{ $asset_domain."/".$image_path."/".$product->image }}" />
                        </div>
                        <div class="col-md-6 col-8 align-self-center">
                            <h4>{{ $product->name }}</h4>
                            <h4 class="price-content">Rp {{ number_format($price_after_discount, 0, 0, '.') }}</h4>
                            <div class="quantity-content">
                                <a href="javascript:;" onclick="minQuantity('{{ $product->id }}')"><i class="fa fa-minus"></i></a>
                                <input type="text" name="quantity" value="{{ $product->quantity }}" class="quantity-text qty-product-{{ $product->id }}" />
                                <a href="javascript:;" onclick="plusQuantity('{{ $product->id }}')"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 align-self-center text-right">
                            <?php 
                            $total_price_product = $price_after_discount * $product->quantity;
                            $total_price += $total_price_product;
                            ?>
                            <h4>Rp {{ number_format($total_price_product, 0, 0, '.') }}</h4>
                        </div>
                        <div class="col-12 text-right">
                            <a href="javascript:;" class="delete-product" data-productId="{{ $product->id }}"><i class="fa fa-trash"></i> Delete</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="col-lg-4">
                <h4 class="text-header mb-4">
                    Shopping Summary
                </h4>
                <div class="content-cart summary">
                    <h5>
                        Total price ({{ DisplayCart::countCart() }} Product)
                        <b class="float-right">Rp {{ number_format($total_price, 0, 0, '.') }}</b>
                    </h5>
                    @if(!empty($voucher["discount"]))
                    <h5>
                        Discount ({{ $voucher["discount"] }}%)
                        <?php 
                        $discount_price = ($voucher["discount"] * $total_price) / 100;
                        ?>
                        <b class="float-right" style="color:red">- Rp {{ number_format($discount_price, 0, 0, '.') }}</b>
                    </h5>
                    <h5>
                        Grand price
                        <?php 
                        $grand_price = $total_price - $discount_price;
                        ?>
                        <b class="float-right">Rp {{ number_format($grand_price, 0, 0, '.') }}</b>
                    </h5>
                    @endif
                    <hr/>
                    <a href="/checkout" class="btn btn-danger w-100">Checkout</a>
                    @if(!empty($voucher["discount"]))
                    <hr/>
                    <a href="javascript:;" onclick="deleteVoucher()" class="voucher-text"><i class="fa fa-trash"></i> Delete Voucher</a>
                    @else
                    <hr/>
                    <a href="javascript:;" data-toggle="modal" data-target="#voucherModal" class="voucher-text"><i class="fa fa-ticket-alt"></i> Use Voucher</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<div class="cart-empty mt-5 mb-5 d-none">
    <div class="container">
        <div class="alert alert-warning" role="alert">
        <h3 class="text-center m-0">Your cart is empty</h3>
        </div>
    </div>
</div>
@include('order.modalVoucher')
@endsection

@push('custom_styles')
<link rel="stylesheet" href="/css/global.css">
<link rel="stylesheet" href="/css/cart.css">
@endpush

@push('custom_scripts')
<script src="/js/global.js"></script>
@if(count($products_cart) != 0)
<script>
    $(function(){
        $('.cart-empty').hide();
    });
</script>
@endif
<script>
    $(function(){
        initDeleteProduct();
        $('.cart-empty').removeClass('d-none');
    });
</script>
<script>
    function minQuantity(product_id){
        var text_quantity = $('.qty-product-'+product_id).val();
        var quantity = parseInt(text_quantity);
        if(quantity != 1){
            quantity -= 1;
            $('.qty-product-'+product_id).val(quantity);
            updateQuantity(product_id, quantity);
        }
    }
    function plusQuantity(product_id){
        var text_quantity = $('.qty-product-'+product_id).val();
        var quantity = parseInt(text_quantity);
        quantity += 1;
        $('.qty-product-'+product_id).val(quantity);
        updateQuantity(product_id, quantity);
    }
    function updateQuantity(product_id, quantity){
        $.ajax({
            url: "{{url('/cart/update')}}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {product_id: product_id, quantity: quantity},
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

                $('.cart_count').html("<span>"+result.totalCart+"</span>");

                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
        });
    }
    function initDeleteProduct() {
        var items = document.getElementsByClassName('delete-product');
        for (var x = 0; x < items.length; x++) {
            var item = items[x];
            item.addEventListener('click', function(fn) {
                var productId  = $(this).attr("data-productId");
                $('#product-'+productId).fadeOut();
                $('#product-'+productId).remove();
                countContent = $('.product-list').length;
                if(countContent == 0){
                    $('.cart').hide();
                    $('.cart-empty').show();
                }
                $.ajax({
                    url: "{{url('/cart/delete')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {product_id: productId},
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
                        
                        $('.cart_count').html("<span>"+result.totalCart+"</span>");
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }
                });
            });
        }
    }
    function deleteVoucher(){
        $.ajax({
            url: "{{url('/voucher/delete')}}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
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

                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
        });
    }
</script>
@endpush