@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
<div class="footer-content mb-5">
    <div class="banner position-relative">
        <img src="/images/banner_background.jpg" class="img-fluid" />
        <div class="overlay position-absolute h-100 w-100 d-flex justify-content-center align-items-center">
            <h1>Product Catalogue</h1>
        </div>
    </div>
    <div class="container">
        <div class="videos">
            @if(count($catalogs) != 0)
            <div class="row">
                @foreach($catalogs as $catalog)
                <div class="col-sm-3">
                    <div class="content mt-4">
                        <a href="/download/{{ $catalog->url }}">
                            <div class="image position-relative">
                                <img src="{{ $asset_domain."/".$thumb_path."/".$catalog->thumb_image }}" class="img-fluid" />
                                <span>Catalogue</span>
                            </div>
                            <h5 class="pt-3 pb-3">{{ $catalog->title }}</h5>
                        </a>
                    </div>
                </div>
                @endforeach
                <div class="col-sm-12">
                    <div class="mt-3">
                        {{ $catalogs->links() }}
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