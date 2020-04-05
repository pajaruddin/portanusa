@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
<div class="footer-content mb-5">
    <div class="container">
        <div class="article mt-4 mb-4">
            <div class="image">
                <img src="{{ $asset_domain."/".$banner_path."/".$article->banner_image }}" class="img-fluid w-100" />
            </div>
            <h3 class="font-weight-normal mt-3">
                {{ $article->title }}
            </h3>
            <div class="desc mt-3">
                {!! $article->description !!}
            </div>

            <!-- Recently Viewed -->
            @if(count($article_products) != 0)
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
                                    <?php $products = $article_products; ?>
                                    @include('product.listOtherProduct')
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('custom_styles')
<link rel="stylesheet" href="/css/global.css">
<link rel="stylesheet" href="/css/product_detail.css">
<link rel="stylesheet" href="/css/footerContent.css">
@endpush

@push('custom_scripts')
<script src="/js/global.js"></script>

@endpush