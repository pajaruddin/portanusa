@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
<div class="footer-content mb-5">
    <div class="banner position-relative">
        <img src="/images/banner_background.jpg" class="img-fluid" />
        <div class="overlay position-absolute h-100 w-100 d-flex justify-content-center align-items-center">
            <h1>{{ $article_category->name }}</h1>
        </div>
    </div>
    <div class="container">
        <div class="videos">
            @if(count($articles) != 0)
            <div class="row">
                @foreach($articles as $article)
                <div class="col-md-6 col-lg-3">
                    <div class="content mt-4">
                        <a href="/article/{{ $article_category->url."/".$article->url }}">
                            <div class="image position-relative">
                                <img src="{{ $asset_domain."/".$banner_path."/".$article->banner_image }}" class="img-fluid" />
                                <span>Article</span>
                            </div>
                            <h5 class="pt-3 pb-3">{{ $article->title }}</h5>
                        </a>
                    </div>
                </div>
                @endforeach
                <div class="col-sm-12">
                    <div class="mt-3">
                        {{ $articles->links() }}
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-secondary text-center mt-4" role="alert">
                There is no data
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('custom_styles')
<link rel="stylesheet" href="/css/global.css">
<link rel="stylesheet" href="/css/footerContent.css">
@endpush

@push('custom_scripts')
<script src="/js/global.js"></script>

@endpush