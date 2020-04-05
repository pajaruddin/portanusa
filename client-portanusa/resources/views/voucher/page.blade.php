@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
<div class="cart mt-5 mb-5">
    <div class="footer-content mb-5">
        <div class="banner position-relative">
            <img src="/images/banner_background.jpg" class="img-fluid" />
            <div class="overlay position-absolute h-100 w-100 d-flex justify-content-center align-items-center">
                <h1>Vouchers</h1>
            </div>
        </div>
    </div>
    <div class="container">
        @if(count($vouchers) != 0)
        <div class="list-voucher">
            <div class="row">
            @foreach($vouchers as $voucher)
            <div class="col-sm-4">
                <div class="card w-100 mb-3">
                    <img class="card-img-top" src="{{ $asset_domain.'/'.$VoucherImagePath.'/'.$voucher->banner }}">
                    <div class="card-body">
                        <h4 class="card-title">{{ $voucher->name }}</h4>
                        <p class="card-text">
                            {!! $voucher->description !!}
                        </p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Voucher Code : <b>{{ $voucher->code }}</b></li>
                        <li class="list-group-item">Discount : <b>{{ $voucher->discount }}%</b></li>
                        <li class="list-group-item">Period : <b>{{ date('j M Y', strtotime($voucher->date_start))." - ".date('j M Y', strtotime($voucher->date_end)) }}</b></li>
                        <li class="list-group-item">Minimum Transaction : <b>Rp {{ number_format($voucher->minimum_amount, 0, 0, '.') }}</b></li>
                    </ul>
                </div>
            </div>
            @endforeach
            </div>
        </div>
        @else
        <div class="alert alert-secondary text-center mt-4" role="alert">
            There is no data
        </div>
        @endif
    </div>
</div>
@endsection

@push('custom_styles')
<link rel="stylesheet" href="/css/global.css">
<link rel="stylesheet" href="/css/footerContent.css">
<link rel="stylesheet" href="/css/cart.css">
@endpush

@push('custom_scripts')
<script src="/js/global.js"></script>
@endpush