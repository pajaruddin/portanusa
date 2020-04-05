@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')

<!-- Home -->

<div class="home">
    <div class="home_background parallax-window" data-parallax="scroll" data-image-src="/images/shop_background.jpg"></div>
    <div class="home_overlay"></div>
    <div class="home_content d-flex flex-column align-items-center justify-content-center">
        <h2 class="home_title">{{ $banner_title }}</h2>
    </div>
</div>

<!-- Shop -->

<div class="shop">
    <div class="container">
        <div class="row">
            <div class="col-md-3 mb-3">

                <!-- Shop Sidebar -->
                <div class="shop_sidebar">
                    @if(count($subject_child) != 0)
                    <div class="sidebar_section">
                        <div class="sidebar_title">Related Subjects</div>
                        <ul class="sidebar_categories">
                            @foreach($subject_child as $child)
                            <li><a href="/product/subject/{{ $child->url }}">{{ $child->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="sidebar_section filter_by_section">
                        <div class="sidebar_title">Filter By</div>
                        <div class="sidebar_subtitle">Price</div>
                        <div class="filter_price">
                            <h6>
                            <span id="priceVal">Rp. 0</span>
                            </h6>
                            <input id="price" type="text" value="" data-slider-min="0" data-slider-max="10000000" data-slider-step="1000000" data-slider-value="0"/>
                            <h6>
                            <a href="javascript:;" class="float-right" id="resetPrice" style="color:red;cursor:pointer;opacity:0">reset</a>
                            </h6>
                            <h6 style="color:#868686" class="font-weight-light">*slide to change the price</h6>
                        </div>
                        @if($total_pre_order > 0)
                        <div style="cursor:pointer" onclick="filterStockStatus()" id="filterStockStatus" class="sidebar_subtitle {{ (!empty($filter['stockStatus']) ? 'active' : '') }}">Pre Order ({{ $total_pre_order }})</div>
                        @endif
                        @if($total_second > 0)
                        <div style="cursor:pointer" onclick="filterStatus()" id="filterStatus" class="sidebar_subtitle {{ (!empty($filter['status']) ? 'active' : '') }}">Product Refurbished ({{ $total_second }})</div>
                        @endif
                    </div>
                </div>

            </div>

            <div class="col-md-9">
                
                <!-- Shop Content -->

                <div class="shop_content">
                    <div class="shop_bar clearfix">
                        <div class="shop_product_count"><span>{{ count($all_products) }}</span> products found</div>
                        <div class="shop_sorting">
                            <span>Sort by:</span>
                            <ul>
                                <li>
                                    <span class="sorting_text">Newest<i class="fas fa-chevron-down"></i></span>
                                    <ul>
                                        <li class="shop_sorting_button newest" onclick="sortProduct('newest')">Newest</li>
                                        <li class="shop_sorting_button alphabet" onclick="sortProduct('alphabet')">Alphabet</li>
                                        <li class="shop_sorting_button high_price" onclick="sortProduct('high_price')">Highest Price</li>
                                        <li class="shop_sorting_button low_price" onclick="sortProduct('low_price')">Lowest Price</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="product_grid product-view">
                        @include('product.listProduct')
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
    
@endsection

@push('plugin_styles')
<link rel="stylesheet" href="/plugins/bootstrap-slider/bootstrap-slider.css">
@endpush
@push('plugin_scripts')
<script src="/plugins/parallax-js-master/parallax.min.js"></script>
<script src="/plugins/bootstrap-slider/bootstrap-slider.js"></script>
<script src="/plugins/currency/currency.js"></script>
@endpush


@push('custom_styles')
<link rel="stylesheet" href="/css/global.css">
<link rel="stylesheet" href="/css/product.css">
@endpush
@push('custom_scripts')
<script src="/js/global.js"></script>
<script>
$(function(){
    initFavs();
    
    $("#price").slider({});
    $("#price").on("slide", function(slideEvt) {
      $('#price').val(slideEvt.value);
      if(slideEvt.value == 10000000){
          $("#priceVal").html('<i class="fa fa-chevron-right"></i>= Rp. ' + CurrencyFormat(slideEvt.value));
          $('#resetPrice').css('opacity','1');
      }else if(slideEvt.value == 0){
          $("#priceVal").html('Rp. ' + CurrencyFormat(slideEvt.value));
          $('#resetPrice').css('opacity','0');
      }else{
          $("#priceVal").html('<i class="fa fa-chevron-left"></i> Rp. ' + CurrencyFormat(slideEvt.value));
          $('#resetPrice').css('opacity','1');
      }
      filterPrice();
    });
    $("#resetPrice").click(function(e) {
       var minval = $("#price").data('slider').min;
       $("#price").slider('setValue', minval);
       $("#priceVal").html('Rp. 0');
       $('#resetPrice').css('opacity','0');
       $('#price').val(0);
       filterPrice();
    });
});
</script>
@if(!empty($filter['orderBy']))
<script>
    var label = $('.{{ $filter["orderBy"] }}').html();
    $('.sorting_text').html(label + '<i class="fas fa-chevron-down"></i>');
</script>
@endif
@include('product.listScript')
@endpush