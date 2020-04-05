@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
<!-- Single Product -->
<?php
$price = DisplayProductPrice::getPrice($product->id);
$product_discount = DisplayProductPrice::getDiscount($product->id, $price);

$price_after_discount = $product_discount['price'];

$product_status = DisplayProductCart::getStatus($product->id, $price);
?>
<div class="single_product">
    <div class="container">
        <div class="row">

            @if(count($product_images) != 0)
            <!-- Images -->
            <div class="col-md-4 col-lg-2">
                <div class="d-md-none">
                    <div class="row">
                        @foreach($product_images as $image)
                        <div class="col-6">
                            <img src="{{ $asset_domain."/".$image_path."/".$image->image }}" alt="" class="img-thumbnail mb-3" />
                        </div>
                        @endforeach    
                    </div>
                </div>
                <ul class="image_list d-none d-md-block">
                    @foreach($product_images as $image)
                    <li data-image="{{ $asset_domain."/".$image_path."/".$image->image }}"><img src="{{ $asset_domain."/".$image_path."/".$image->image }}" alt=""></li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Selected Image -->
            <div class="col-md-8 col-lg-4">
                <div class="image_selected mb-5"><img id="image-base" src="{{ $asset_domain."/".$image_path."/".$product_image_base->image }}" alt=""></div>
            </div>

            <!-- Description -->
            <div class="col-md-12 col-lg-6">
                <div class="product_description">
                    <div class="product_category">{{ $category->name }}</div>
                    <div class="product_name">
                        {{ $product->name }}
                        @if(Auth::check())
                        <div class="ml-2 product_fav {{ (DisplayWishlist::checkProduct($product->id) ? 'active' : '') }}" data-productId="{{ $product->id }}"><i class="fas fa-heart"></i></div>
                        @else
                        <a href="javascript:;" onclick="showLoginModal()"><div class="ml-2 product_fav {{ (DisplayWishlist::checkProduct($product->id) ? 'active' : '') }}" data-productId="{{ $product->id }}"><i class="fas fa-heart"></i></div></a>
                        @endif
                    </div>
                    <span href="javascript:;" class="badge badge-{{ ($product->status == 'New' ? 'primary' : 'danger') }}" style="font-size:16px">{{ $product->status }}</span>
                    <div class="product_text"><p>{!! $product->highlight !!}</p></div>
                    @if(!empty($package_items) && count($package_items) != 0)
                    <div class="product-package">
                        <h4>Package Deal</h4>
                        <h5 class="active"><a href="javascript:;">Without Accessories</a></h5>
                        @foreach($package_items as $item)
                        <h5><a href="/package/{{ $item->url }}">{{ $item->label }}</a></h5>
                        @endforeach
                    </div>
                    @endif
                    <div class="order_info d-flex flex-row">
                        <form action="#">
                            @if($price != 0)
                            <div class="product_price">
                                @if($price != $price_after_discount)
                                Rp {{ number_format($price_after_discount,0,0,'.') }}
                                <span>Rp {{ number_format($price,0,0,'.') }}</span>
                                @else
                                Rp {{ number_format($price,0,0,'.') }}
                                @endif
                            </div>
                            @else
                            <div class="product_price">{{ $product_status['label'] }}</div>
                            @endif
                            <div class="button_container">
                                <input type="hidden" class="product-id" value="{{ $product->id }}" />
                                @if($product_status['add_to_cart'])
                                <button type="button" onclick="addToCart()" class="button cart_button">{{ $product_status['label_cart'] }}</button>
                                @else
                                <span class="ml-3" style="font-size:20px">{{ $product_status['label_status'] }}</span>
                                @endif
                                @if($product->stock_status_id == 2)
                                <span class="ml-3" style="font-size:20px">{{ $product->pre_order_text }} Weeks</span>
                                @endif
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <ul class="nav nav-tabs mt-5 d-flex justify-content-center" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="desc-tab" data-toggle="tab" href="#desc" role="tab" aria-controls="desc" aria-selected="true">Description</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="discussion-tab" data-toggle="tab" href="#discussion" role="tab" aria-controls="discussion" aria-selected="false">Discussion</a>
            </li>
        </ul>
        <div class="tab-content mt-4" id="myTabContent">
            <div class="tab-pane fade show active" id="desc" role="tabpanel" aria-labelledby="desc-tab">
                <div class="product-description table-responsive">
                    {!! $product->description !!}
                </div>
            </div>
            <div class="tab-pane fade" id="discussion" role="tabpanel" aria-labelledby="discussion-tab">
                <div class="product-discussion">
                    <div class="message-content mb-3">
                        <div class="d-flex justify-content-center">
                            <h3 class="text-center">What's your question ?</h3>
                        </div>
                        <form method="POST" role="form" id="productDiscussionForm" action="{{ url('product/discussion') }}" autocomplete="off">
                            {{ csrf_field() }}
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="customer_id" value="{{ Auth::id() }}">
                            <textarea class="form-control" rows="4" name="message" id="message"></textarea>
                            <div class="text-right mt-3">
                                @if(Auth::check())
                                <button role="button" class="btn btn-primary" type="submit">Submit</button>
                                @else
                                <a href="javascript:;" onclick="showLoginModal()" class="btn btn-primary">Sign in</a>
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="list-message">
                    @if(count($product_discussions_parent) != 0)
                        @foreach($product_discussions_parent as $discussion_parent)
                        <div class="content mb-3">
                            <h4>{{ (!empty($discussion_parent->customer_id) ? $discussion_parent->first_name.' '.$discussion_parent->last_name : $discussion_parent->user_first_name.' '.$discussion_parent->user_last_name) }}</h4>
                            <h6><i class="far fa-calendar-alt"></i> {{ date('d/m/Y', strtotime($discussion_parent->created_at)) }}</h6>
                            <h5>
                            {!! $discussion_parent->text !!}
                            </h5>
                            @if(count($product_discussions_child[$discussion_parent->id]) != 0)
                            @foreach($product_discussions_child[$discussion_parent->id] as $discussion_child)
                            <div class="content-reply">
                                <h4>{{ (!empty($discussion_child->customer_id) ? $discussion_child->first_name.' '.$discussion_child->last_name : $discussion_child->user_first_name.' '.$discussion_child->user_last_name) }}</h4>
                                <h6><i class="far fa-calendar-alt"></i> {{ date('d/m/Y', strtotime($discussion_child->created_at)) }}</h6>
                                <h5>
                                {!! $discussion_child->text !!}
                                </h5>
                            </div>
                            @endforeach
                            @endif
                            @if(Auth::check())
                            <div class="content-reply">
                                <form method="POST" role="form" id="productDiscussionReplyForm-{{$discussion_parent->id}}" action="{{ url('product/discussion') }}" autocomplete="off">
                                {{ csrf_field() }}
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="customer_id" value="{{ Auth::id() }}">
                                <input type="hidden" name="parent" value="{{ $discussion_parent->id }}">
                                <textarea class="form-control" placeholder="Write your comment" name="message" id="reply"></textarea>
                                <div class="text-right mt-3">
                                    <button class="btn btn-outline-secondary btn-sm" type="submit">Submit</button>
                                </div>
                                </form>
                            </div>
                            @endif
                            </div>
                        @endforeach
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recently Viewed -->
@if(count($products_recent) != 0)
<div class="viewed" style="background:transparent">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="viewed_title_container">
                    <h3 class="viewed_title">Recent View</h3>
                </div>

                <div class="viewed_slider_container">
                    
                    <!-- Recently Viewed Slider -->

                    <div class="owl-carousel owl-theme viewed_slider">
                        <?php $products = $products_recent; ?>
                        @include('product.listOtherProduct')
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endif
@if(count($product_related) != 0)
<div class="viewed">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="viewed_title_container">
                    <h3 class="viewed_title">Related Product</h3>
                </div>

                <div class="viewed_slider_container">
                    
                    <!-- Recently Viewed Slider -->

                    <div class="owl-carousel owl-theme viewed_slider">
                        <?php $products = $product_related; ?>
                        @include('product.listOtherProduct')
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('plugin_scripts')
<script src="/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="/plugins/jquery-form/jquery.form.min.js"></script>
<script src="/plugins/zoom-image/jquery.elevatezoom.js"></script>
@endpush

@push('custom_styles')
<link rel="stylesheet" href="/css/global.css">
<link rel="stylesheet" href="/css/product_detail.css">
{{-- <link rel="stylesheet" href="/styles/product_styles.css"> --}}
@endpush

@push('custom_scripts')
<script src="/js/global.js"></script>
<script>
$(function(){
    $( "#productDiscussionForm" ).validate( {
    rules: {
        message: "required"
    },
    messages: {
        message: "Please enter your message"
    },
    errorElement: "span",
    submitHandler: function(form) {
        $('#productDiscussionForm').ajaxSubmit({
            dataType: 'json',
            success: function (data) {
                if (data.status == 'error') {
                new Noty({
                        type: 'error',
                        text: 'Data unsuccessfully sent',
                        layout: 'center',
                        timeout: 2000,
                        modal: true
                    }).show();
                } else {
                new Noty({
                        type: 'success',
                        text: 'Data successfully sent',
                        layout: 'center',
                        timeout: 2000,
                        modal: true
                    }).show();
                setTimeout(function () {
                    $("#productDiscussionForm")[0].reset();
                    location.reload();
                }, 1000);
                }
            }
        });
        return false;
    }
    });

});
</script>
@if(count($product_discussions_parent) != 0)
@foreach($product_discussions_parent as $discussion_parent)
<script>
  $(function(){
    $( "#productDiscussionReplyForm-{{$discussion_parent->id}}" ).validate( {
      rules: {
        message: "required"
      },
      messages: {
        message: "Please enter your message"
      },
      errorElement: "span",
      submitHandler: function(form) {
        $('#productDiscussionReplyForm-{{$discussion_parent->id}}').ajaxSubmit({
            dataType: 'json',
            success: function (data) {
                if (data.status == 'error') {
                  new Noty({
                        type: 'error',
                        text: 'Data unsuccessfully sent',
                        layout: 'center',
                        timeout: 2000,
                        modal: true
                    }).show();
                } else {
                  new Noty({
                        type: 'success',
                        text: 'Data successfully sent',
                        layout: 'center',
                        timeout: 2000,
                        modal: true
                    }).show();
                  setTimeout(function () {
                      $("#productDiscussionReplyForm-{{$discussion_parent->id}}")[0].reset();
                      location.reload();
                  }, 1000);
                }
            }
        });
        return false;
      }
    });

  });
</script>
@endforeach
@endif
<script>
$(function(){
    initFavs();
    initImage();
    
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
                        $(this).toggleClass("active");
                    }
                });
            });
        }
    }

	function initImage()
	{
		var images = $('.image_list li');
		var selected = $('.image_selected img');

		images.each(function()
		{
			var image = $(this);
			image.on('click', function()
			{
				var imagePath = new String(image.data('image'));
				selected.attr('src', imagePath);
                var width_window = $(window).outerWidth();
                if(width_window > 1024){
                    $("#image-base").elevateZoom({scrollZoom: true, zoomWindowPosition: 1, zoomWindowOffetx: 10});
                }
			});
		});
	}
});
</script>
<script>
    $(function(){
        var width_window = $(window).outerWidth();
        if(width_window > 1024){
            $("#image-base").elevateZoom({scrollZoom: true, zoomWindowPosition: 1, zoomWindowOffetx: 10});
        }
    })
</script>
@include('product.cartScript')
@endpush