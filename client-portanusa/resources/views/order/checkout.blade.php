@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
<div class="cart mt-5 mb-5">
    <div class="container">
        <div class="wizard">
            <div class="wizard-inner">
                <div class="connecting-line"></div>
                <ul class="nav nav-tabs d-flex justify-content-center" role="tablist">
                    <li role="presentation" class="nav-item">
                        <a href="javascript:;" data-toggle="tab" role="tab" title="Shipping Address" class="nav-link active">
                            <span class="round-tab">
                                <i class="fa fa-truck"></i>
                            </span>
                        </a>
                    </li>
                    <li role="presentation" class="nav-item">
                        <a href="javascript:;" data-toggle="tab" role="tab" title="Payment" class="nav-link disabled">
                            <span class="round-tab">
                                <i class="fas fa-money-check"></i>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <form action="/checkout/form" method="POST" autocomplete="off">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-lg-8">
                <div class="content-cart">
                    <h4 class="text-header mb-4 mt-3">
                        Shipping address
                    </h4>
                    <div class="row">
                        <div class="col-sm-7">
                            <select class="form-control ml-0 mb-3 select-shipping" name="shipping" required>
                                <option value="">Select shipping address</option>
                                @if(count($user_shipping_address) != 0)
                                @foreach($user_shipping_address as $user_address)
                                    <option value="{{ $user_address->id }}" {{ (!empty($shipping["address"]) && $shipping["address"] == $user_address->id ? "selected" : "") }}>{{ $user_address->receiver_name." (".$user_address->label.")" }}</option>
                                @endforeach
                                @endif
                            </select>
                            <input type="hidden" class="text-weight" value="{{ $total_weight }}" />
                        </div>
                        <div class="col-sm align-self-center">
                            <h6 class="font-weight-light text-center mb-3">or</h6>
                        </div>
                        <div class="col-sm-4">
                            <a href="/account/shipping" class="btn btn-outline-secondary w-100 mb-3">Create shipping address</a>
                        </div>
                    </div>
                    <div class="shipping-box">
                        @if(!empty($shipping['address']))
                        @include('order.shipping')
                        @endif
    
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                            {!! session('error') !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="content-cart mt-4">
                    <h4 class="text-header mb-4 mt-3">
                        Product in cart ({{ DisplayCart::countCart() }})
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
                                    Total : {{ $product->quantity }}<br/>
                                    @php 
                                    $product_weight = $product->weight * $product->quantity
                                    @endphp
                                    Weight : {{ number_format($product_weight,0,0,'.') }} gram<br/>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 align-self-center text-right">
                                <?php 
                                $total_price_product = $price_after_discount * $product->quantity;
                                ?>
                                <h4>Rp {{ number_format($total_price_product, 0, 0, '.') }}</h4>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4">
                <div class="content-cart mb-4">
                    <h4 class="text-header mb-4 mt-3">
                        Shipping Courier
                    </h4>
                    @if(empty($shipping['couriers']))
                    <div class="alert alert-warning" role="alert">
                        Choose Shipping Address
                    </div>
                    @else
                        @php 
                        $no = 1;
                        @endphp
                        @foreach($shipping['couriers'] as $courier)
                        <div class="form-check">
                            <input class="form-check-input ml-0" onchange="setCost('{{ $courier['value'] }}')" name="courier_type" type="radio" id="courier{{ $no }}" value="{{ $courier['service'] }}" />
                            <label class="form-check-label w-100" for="courier{{ $no }}">
                                JNE 
                                @if($courier['service'] == "CTC")
                                REG
                                @elseif($courier['service'] == "CTCYES")
                                YES
                                @else
                                {{ $courier['service'] }}
                                @endif
                                <span class="float-right">Rp. {{ number_format($courier['value'], 0, 0, '.') }}</span>
                                <br/>
                                {{ $courier['etd'] }} weekday
                            </label>
                        </div>
                        @php 
                        $no++;
                        @endphp
                        @endforeach
                    @endif
                </div>
                <div class="content-cart summary">
                    <h4 class="text-header mb-4 mt-3">
                        Shopping Summary
                    </h4>
                    <h5>
                        Total price ({{ DisplayCart::countCart() }} Product)
                        <b class="float-right">Rp {{ number_format($total_price, 0, 0, '.') }}</b>
                        <input type="hidden" name="total_price" value="{{ $total_price }}" />
                    </h5>
                    <?php $discount_price = 0; ?>
                    @if(!empty($voucher["discount"]))
                    <h5>
                        Discount ({{ $voucher["discount"] }}%)
                        <?php 
                        $discount_price = ($voucher["discount"] * $total_price) / 100;
                        ?>
                        <b class="float-right" style="color:red">- Rp {{ number_format($discount_price, 0, 0, '.') }}</b>
                    </h5>
                    @endif
                    <h5>
                        Shipping Cost
                        <b class="float-right shipping-cost-content">Rp 0</b>
                        <input type="hidden" name="shipping_cost" value="0" class="shipping-cost-text" />
                    </h5>
                    <h5>
                        Total Weight
                        @php 
                        $total_weight = $total_weight / 1000
                        @endphp
                        <b class="float-right">{{ $total_weight }} Kg</b>
                    </h5>
                    <h5>
                        Grand price
                        <?php 
                        $grand_price = $total_price - $discount_price;
                        ?>
                        <b class="float-right grand-price-content">Rp {{ number_format($grand_price, 0, 0, '.') }}</b>
                        <input type="hidden" name="grand_price" class="grand-price-text" value="{{ $grand_price }}" />
                    </h5>
                    <hr/>
                    <div class="form-check mb-3">
                        <input class="form-check-input ml-0" name="tax_invoice" type="checkbox" value="T" id="taxInvoice">
                        <label class="form-check-label" for="taxInvoice">
                            Include Tax Invoice
                        </label>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 submit-checkout" disabled>Checkout</button>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
<div class="loading-section justify-content-center align-items-center d-none">
    <img src="/images/loading.svg" class="img-fluid" />
</div>
@endsection

@push('plugin_scripts')
<script src="/plugins/currency/currency.js"></script>
@endpush

@push('custom_styles')
<link rel="stylesheet" href="/css/global.css">
<link rel="stylesheet" href="/css/cart.css">
@endpush

@push('custom_scripts')
<script src="/js/global.js"></script>
<script>
    $(function(){
        $(".select-shipping").change(function(){
            var shipping = $('.select-shipping').val();
            var weight = $('.text-weight').val();
            
            if(shipping != ""){
                $('.loading-section').removeClass('d-none');
                $('.loading-section').addClass('d-flex');
                $.ajax({
                    url: "{{url('/shipping-address/get-cost')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {shipping: shipping, weight: weight},
                    type: 'POST',
                    dataType: 'json',
                    success: function (result) {
                        if (result.status == "success") {
                            location.reload();
                        }
                    }
                });
            }else{
                new Noty({
                    type: 'error',
                    text: 'Please choose shipping address',
                    layout: 'center',
                    timeout: 2000,
                    modal: true
                }).show();
            }
        });
    });

    function setCost(price){
        var total_price = parseInt($('.grand-price-text').val()) - parseInt($('.shipping-cost-text').val());
        var grand_price = parseInt(price) + total_price;
        
        $('.shipping-cost-text').val(price);
        $('.grand-price-text').val(grand_price);
        $('.shipping-cost-content').html("Rp. "+ CurrencyFormat(price));
        $('.grand-price-content').html("Rp. "+ CurrencyFormat(grand_price));
        $('.submit-checkout').removeAttr('disabled');
    }
</script>
@endpush