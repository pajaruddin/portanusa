@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
    <div class="footer-content mb-5">
    <div class="banner position-relative">
        <img src="/images/banner_2_background.jpg" class="img-fluid" />
        <div class="overlay position-absolute h-100 w-100 d-flex justify-content-center align-items-center">
            <h1>Returning Of Units</h1>
        </div>
    </div>
    <div class="container">
        <div class="about-us-content mt-4 mb-4">
            <h3 class="font-weight-normal text-center">Ready To Serve You</h4>
            <div class="row mt-5">
                <div class="col-sm-6">
                    <h4 class="font-weight-light mt-4">
                        Customer satisfaction becomes our barometer to continue to innovate day by day. Of our many superior products and most of them are electronic products, of course there is no problem with manufacturing defects. 
                    </h4>
                </div>
                <div class="col-sm-6">
                    <h4 class="font-weight-light mt-4">
                        Departing from this possibility, then if you want to send back a product that is not functioning / not functioning as it should, we will gladly accept a return shipment from you.
                    </h4>
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
