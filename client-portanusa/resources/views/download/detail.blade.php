@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
<div class="footer-content mb-5">
    <div class="container">
        <div class="article mt-4 mb-4">
            <h3 class="font-weight-normal mt-3">
                {{ $catalog->title }}
            </h3>
            <div class="desc mt-3">
                <iframe style="width:100%; height:620px;" src="{{ $catalog->document_url }}" frameborder="0" allowfullscreen></iframe>
            </div>
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