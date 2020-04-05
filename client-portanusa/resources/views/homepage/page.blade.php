@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
	@if(!empty($banners) && count($banners) != 0)
  	<!-- Banner -->
	<div class="banner-home">
		<div id="banner-slide" class="carousel slide" data-ride="carousel">
			<div class="carousel-inner">
				@php $no = 1; @endphp
				@foreach($banners as $banner)
				<div class="carousel-item {{ ($no == 1 ? 'active' : '') }}">
					<div class="carousel-image">
						<img class="d-block" src="{{ $asset_domain.'/'.$bannerPath.'/'.$banner->image }}" alt="Image {{ $banner->title }}">
					</div>
					<div class="carousel-caption">
						<div class="caption">
							<div class="container">
								<h5 class="mb-4">{{ $banner->title }}</h5>
								<p class="mb-4">{{ $banner->subtitle }}</p>
								<a href="{{ $banner->url }}">Find Out More</a>
							</div>
						</div>
					</div>
				</div>
				@php $no++; @endphp
				@endforeach
			</div>
			<a class="carousel-control-prev" href="#banner-slide" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="carousel-control-next" href="#banner-slide" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>
	</div>
	@endif

	@if(count($services) != 0)
	<div class="container">
		<div class="service mt-5 text-center">
			<h4>Why Choose Portanusa ?</h4>
			<h6>You will get many advantages from buying at Portanusa</h6>
			<div class="border-text mb-4"></div>
			<div class="row">
				@foreach($services as $service)
				<div class="col-sm-3">
					<img src="{{ $asset_domain."/".$servicePath."/".$service->image }}" class="img-fluid" width="120px"/>
					<h5 class="mt-3">{{ $service->title }}</h5>
					<h6 class="mt-2">{{ $service->description }}</h6>
				</div>
				@endforeach
			</div>
		</div>
	</div>
	@endif
	
	{{-- @if(count($vouchers) != 0)
	<div class="container">
        <div class="list-voucher mt-5">
			<div class="row">
				<div class="col-sm-2 align-self-center">
					<h3 class="font-weight-light mb-3">
						Vouchers
						<br/>
						<a href="/voucher">See more <i class="fa fa-chevron-right"></i></a>
					</h3>
				</div>
				<div class="col-sm-10">
					<div class="row">
						@foreach($vouchers as $voucher)
						<div class="col-lg-3 col-md-6">
							<div class="card w-100 mb-3">
								<div class="card-image position-relative">
									<img class="card-img-top" src="{{ $asset_domain.'/'.$VoucherImagePath.'/'.$voucher->banner }}">
									<h4 class="card-title position-absolute">{{ $voucher->name }}</h4>
								</div>
								<div class="card-body">
									<p class="card-text">
										Voucher Code : <b>{{ $voucher->code }}</b>
									</p>
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
        </div>
    </div>
	@endif --}}

	<!-- Deals of the week -->
	<div class="deals_featured mt-5">
		<div class="container">
			<div class="row">
				@if(!empty($event))
				<div class="col-md-5">
					<!-- Deals -->

					<div class="deals w-100">
						<div class="deals_title">
							{{ $event->name }}
							<br/>
							<a href="/product/event/{{ $event->url }}">See more <i class="fa fa-chevron-right"></i></a>
						</div>
						<div class="deals_slider_container">
							@if(count($sale_products) != 0)
							<!-- Deals Slider -->
							<div class="owl-carousel owl-theme deals_slider">
								@foreach($sale_products as $sale)
								@php
								$price = DisplayProductPrice::getPrice($sale->id);
								$product_discount = DisplayProductPrice::getDiscount($sale->id, $price);

								$price_after_discount = $product_discount['price'];

								$product_status = DisplayProductCart::getStatus($sale->id, $price);
								@endphp
								<!-- Deals Item -->
								<div class="owl-item deals_item">
									<div class="deals_image">
										<a href="/base/{{ $sale->url }}">
											<img src="{{ $asset_domain."/".$image_path."/".$sale->image }}" alt="">
										</a>
									</div>
									<div class="deals_content">
										<div class="deals_info_line d-flex flex-row justify-content-start">
											<div class="deals_item_category"><a href="/product/category/{{ $sale->category_url }}">{{ $sale->category_name }}</a></div>
											<div class="deals_item_price_a ml-auto">
												@if($price != $price_after_discount)
												Rp {{ number_format($price,0,0,'.') }}
												@endif
											</div>
										</div>
										<div class="deals_info_line d-flex flex-row justify-content-start">
											<div class="deals_item_name"><a href="/base/{{ $sale->url }}">{{ str_limit($sale->name, 10) }}</a></div>
											<div class="deals_item_price ml-auto">Rp {{ number_format($price_after_discount,0,0,'.') }}</div>
										</div>
										{{-- <div class="available">
											<div class="available_line d-flex flex-row justify-content-start">
												<div class="available_title">Available: <span>6</span></div>
												<div class="sold_title ml-auto">Already sold: <span>28</span></div>
											</div>
											<div class="available_bar"><span style="width:17%"></span></div>
										</div> --}}
										<div class="deals_timer d-flex flex-row align-items-center justify-content-start">
											<div class="deals_timer_title_container">
												<div class="deals_timer_title">Hurry Up</div>
												<div class="deals_timer_subtitle">Offer ends in:</div>
											</div>
											<div class="deals_timer_content ml-auto">
												<div class="deals_timer_box clearfix" data-target-time="{{ date('M d, Y', strtotime($event->date_end)) }}">
													<div class="deals_timer_unit">
														<div id="deals_timer1_hr" class="deals_timer_hr"></div>
														<span>hours</span>
													</div>
													<div class="deals_timer_unit">
														<div id="deals_timer1_min" class="deals_timer_min"></div>
														<span>mins</span>
													</div>
													<div class="deals_timer_unit">
														<div id="deals_timer1_sec" class="deals_timer_sec"></div>
														<span>secs</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								@endforeach

							</div>
							@endif

						</div>

						<div class="deals_slider_nav_container">
							<div class="deals_slider_prev deals_slider_nav"><i class="fas fa-chevron-left ml-auto"></i></div>
							<div class="deals_slider_next deals_slider_nav"><i class="fas fa-chevron-right ml-auto"></i></div>
						</div>
					</div>

				</div>
				@endif
				
				@php $total_product_status = 0 @endphp
				@foreach($status_arr as $status)
				@if(count($products_status[$status]) > 0)
				@php $total_product_status++ @endphp
				@endif
				@endforeach
				@if($total_product_status > 0)
				<div class="col-md">
					<!-- Featured -->
					<div class="featured w-100">
						<div class="tabbed_container">
							<div class="tabs">
								<ul class="clearfix">
									@php $no = 1 @endphp
									@foreach($status_arr as $status)
									@if(count($products_status[$status]) > 0)
									<li class="{{ ($no == 1 ? 'active' : '') }}">{{ $status }}</li>
									@php $no++ @endphp
									@endif
									@endforeach
								</ul>
								<div class="tabs_line"><span></span></div>
							</div>

							@php $no = 1 @endphp
							@foreach($status_arr as $status)
							@if(count($products_status[$status]) > 0)
							<!-- Product Panel -->
							<div class="ml-0 mr-0 product_panel panel {{ ($no == 1 ? 'active' : '') }}">
								<div class="w-100 pr-3 pl-3">
									<h5 class="text-right mt-3 mb-0"><a href="/product/status/{{ str_slug($status, "_") }}">See More</a></h5>
									<div class="row">
										@php 
										$no_loop = 1
										@endphp
										@foreach($products_status[$status] as $product)
										<div class="col-lg-3 col-md-4 col-sm-6 col-12">
											<?php
											$price = DisplayProductPrice::getPrice($product->id);
											$product_discount = DisplayProductPrice::getDiscount($product->id, $price);
									
											$price_after_discount = $product_discount['price'];
									
											$product_status = DisplayProductCart::getStatus($product->id, $price);

											$status_style = "";
											if($price != $price_after_discount){
												$status_style .= " discount";
											}
											if($product->status == "New"){
												$status_style .= " is_new";
											}
											?>
											<div class="product_item{{$status_style}} {{ ($no_loop % 4 == 0 ? "border-none" : "") }} mt-4 mb-4">
												<a href="/base/{{ $product->url }}" tabindex="0"><div class="product_image d-flex flex-column align-items-center justify-content-center"><img src="{{ $asset_domain."/".$image_path."/".$product->image }}" alt=""></div></a>
												<div class="product_content">
													@if($price != 0)
														<div class="product_price">
															@if($price != $price_after_discount)
															Rp {{ number_format($price_after_discount,0,0,'.') }}
															<br/>
															<span>Rp {{ number_format($price,0,0,'.') }}</span>
															@else
															Rp {{ number_format($price,0,0,'.') }}
															@endif
														</div>
													@else
													<div class="product_price">{{ $product_status['label'] }}</div>
													@endif
													<div class="product_name"><div><a href="/base/{{ $product->url }}" tabindex="0">{{ str_limit($product->name, 20) }}</a></div></div>
												</div>
												@if(Auth::check())
												<div class="product_fav {{ (DisplayWishlist::checkProduct($product->id) ? 'active' : '') }}" data-productId="{{ $product->id }}"><i class="fas fa-heart"></i></div>
												@else
												<a href="javascript:;" onclick="showLoginModal()"><div class="product_fav" data-productId="{{ $product->id }}"><i class="fas fa-heart"></i></div></a>
												@endif
												<ul class="product_marks">
													<li class="product_mark product_discount">-{{ $product_discount['discount'] }}%</li>
													<li class="product_mark product_new">new</li>
												</ul>
											</div>
										</div>
										@php 
										$no_loop++;
										@endphp
										@endforeach
									</div>
								</div>
							</div>
							@php $no++ @endphp
							@endif
							@endforeach

						</div>
					</div>
				</div>
				@endif
			</div>
		</div>
	</div>

	@if(!empty($highlight_categories) && count($highlight_categories))
	<div class="highlight-content p-5 mt-4">
		<div class="container">
			<h2 class="text-center mb-5">
				Portanusa Highlight Category
				<br/>
				<span>Find the best Products or Solutions in Portanusa</span>
			</h2>
			<div class="tabbed_container">
				<div class="tabs">
					<ul class="clearfix d-flex justify-content-center">
						@php $no = 1 @endphp
						@foreach($highlight_categories as $highlight)
						<li class="{{ ($no == 1 ? 'active' : '') }}">{{ $highlight->name }}</li>
						@php $no++ @endphp
						@endforeach
					</ul>
					<div class="tabs_line"><span></span></div>
				</div>
	
				@php $no = 1 @endphp
				@foreach($highlight_categories as $highlight)
				
				<!-- Product Panel -->
				<div class="ml-0 mr-0 product_panel panel {{ ($no == 1 ? 'active' : '') }}">
					@if(!empty($highlight_products[$highlight->id]) && count($highlight_products[$highlight->id]) != 0)
					<div class="mt-4">
						<div class="row">
							<div class="col-md">
								@php 
								$first_product = $highlight_products[$highlight->id][0];
								@endphp
								<a href="/base/{{ $first_product->url }}">
									<div class="highlight h-100 p-4 mt-2">
										<div class="content-box">
											<div class="image">
												<img src="{{ $asset_domain."/".$image_path."/".$first_product->image }}" alt="" class="img-fluid" />
											</div>
											<h5>{{ str_limit($first_product->name, 15) }}</h5>
										</div>
									</div>
								</a>
							</div>
							@if(count($highlight_products[$highlight->id]) > 1)
							<div class="col-md">
								@php 
								$second_product = $highlight_products[$highlight->id][1];
								@endphp
								<a href="/base/{{ $second_product->url }}">
									<div class="highlight h-50 p-4">
										<div class="content-box">
											<div class="image">
												<img src="{{ $asset_domain."/".$image_path."/".$second_product->image }}" alt="">
											</div>
											<h5>{{ str_limit($second_product->name, 15) }}</h5>
										</div>
									</div>
								</a>
								@if(count($highlight_products[$highlight->id]) > 2)
								<div class="row">
								@foreach($highlight_products[$highlight->id] as $key => $product_highlight)
								@if($key >= 2)
								<div class="col-md-6">
									<a href="/base/{{ $product_highlight->url }}">
										<div class="pt-4 h-100">
											<div class="highlight h-100 p-4 pt-0">
												<div class="content-box">
													<div class="image">
														<img src="{{ $asset_domain."/".$image_path."/".$product_highlight->image }}" alt="">
													</div>
													<h5>{{ str_limit($product_highlight->name, 15) }}</h5>
												</div>
											</div>
										</div>
									</a>
								</div>
								@endif
								@endforeach
								</div>
								@endif
							</div>
							@endif
						</div>
						<h5 class="text-right mt-5 mb-0"><a href="/product/category/{{ $highlight->url }}">See More</a></h5>
					</div>
					@endif
				</div>
				@php $no++ @endphp
				@endforeach
	
			</div>
		</div>
	</div>
	@endif
	<!-- Product Category -->
	<?php $no = 1; ?>
	@foreach($categories as $category_id)
	@if(count($products_category[$category_id->id]) != 0)
	<div class="container">
		<div class="viewed product-list mt-5 p-4" style="background: #ffffff">
			<div class="row">
				<div class="col">
					<div class="viewed_title_container">
						<h3 class="viewed_title">
							{{ $category[$category_id->id]->name }}
							<a href="/product/category/{{ $category[$category_id->id]->url }}">See more <i class="fa fa-chevron-right"></i></a>
						</h3>
					</div>

					<div class="viewed_slider_container">
						
						<!-- Recently Viewed Slider -->

						<div class="">
							<?php $products = $products_category[$category_id->id]; ?>
							@include('product.listOtherProduct')
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<?php $no++; ?>
	@endif
	@endforeach
	
	{{-- <!-- Brands -->

	<div class="brands">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="brands_slider_container">
						
						<!-- Brands Slider -->

						<div class="owl-carousel owl-theme brands_slider">
							
							<div class="owl-item"><div class="brands_item d-flex flex-column justify-content-center"><img src="/images/brands_1.jpg" alt=""></div></div>
							<div class="owl-item"><div class="brands_item d-flex flex-column justify-content-center"><img src="/images/brands_2.jpg" alt=""></div></div>
							<div class="owl-item"><div class="brands_item d-flex flex-column justify-content-center"><img src="/images/brands_3.jpg" alt=""></div></div>
							<div class="owl-item"><div class="brands_item d-flex flex-column justify-content-center"><img src="/images/brands_4.jpg" alt=""></div></div>
							<div class="owl-item"><div class="brands_item d-flex flex-column justify-content-center"><img src="/images/brands_5.jpg" alt=""></div></div>
							<div class="owl-item"><div class="brands_item d-flex flex-column justify-content-center"><img src="/images/brands_6.jpg" alt=""></div></div>
							<div class="owl-item"><div class="brands_item d-flex flex-column justify-content-center"><img src="/images/brands_7.jpg" alt=""></div></div>
							<div class="owl-item"><div class="brands_item d-flex flex-column justify-content-center"><img src="/images/brands_8.jpg" alt=""></div></div>

						</div>
						
						<!-- Brands Slider Navigation -->
						<div class="brands_nav brands_prev"><i class="fas fa-chevron-left"></i></div>
						<div class="brands_nav brands_next"><i class="fas fa-chevron-right"></i></div>

					</div>
				</div>
			</div>
		</div>
	</div> --}}

	<!-- Newsletter -->

	{{-- <div class="newsletter mt-5">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="newsletter_container d-flex flex-lg-row flex-column align-items-lg-center align-items-center justify-content-lg-start justify-content-center">
						<div class="newsletter_title_container">
							<div class="newsletter_icon"><img src="images/send.png" alt=""></div>
							<div class="newsletter_title">Sign up for Newsletter</div>
						</div>
						<div class="newsletter_content clearfix">
							<form action="/newsletter/create" method="POST" autocomplete="off" class="newsletter_form">
							{{ csrf_field() }}
								<input type="email" class="newsletter_input" required="required" name="email" placeholder="Enter your email address">
								<button class="newsletter_button">Subscribe</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div> --}}
@endsection

@push('custom_styles')
<link rel="stylesheet" href="/css/product_detail.css">
<link rel="stylesheet" href="/styles/main_styles.css">
<link rel="stylesheet" href="/css/homepage.css">
@endpush

@push('plugin_styles')
<link rel="stylesheet" type="text/css" href="/plugins/OwlCarousel2-2.2.1/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="/plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="/plugins/OwlCarousel2-2.2.1/animate.css">
<link rel="stylesheet" type="text/css" href="/plugins/slick-1.8.0/slick.css">
@endpush
@push('plugin_scripts')
<script src="/plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
<script src="/plugins/slick-1.8.0/slick.js"></script>
@endpush

@push('custom_scripts')
<script src="/js/custom.js"></script>
@endpush