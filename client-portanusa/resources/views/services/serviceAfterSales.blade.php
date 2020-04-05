@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
    <div class="footer-content mb-5">
    <div class="banner position-relative">
        <img src="/images/banner_2_background.jpg" class="img-fluid" />
        <div class="overlay position-absolute h-100 w-100 d-flex justify-content-center align-items-center">
            <h1>Service and After Sales</h1>
        </div>
    </div>
    <div class="container">
        <div class="about-us-content mt-4 mb-4">
            <h3 class="font-weight-normal text-center">Ready To Serve You</h3>
            <div class="row mt-5">
                <div class="col-sm-6">
                    <h3 class="font-weight-normal">Services Information</h3>
                    <h4 class="font-weight-light mt-4">
                        Portanusa is the only eCommerce that has an Official Service Center with experts who are ready to handle various customer complaints and questions about Hardware & Technology products purchased on our website or store network. Portanusa has also partnered with several leading technology brands as Authorized Service Partners, including: Nutanix, Cisco, Juniper.    
                    </h4>
                </div>
                <div class="col-sm-6">
                    <h3 class="font-weight-normal">Services Type</h3>
                    <h4 class="font-weight-normal mt-3" >* Official Warranty Support</h4>
                    <h4 class="font-weight-light mt-2">Several official brands have collaborated with us. Therefore, we are ready to serve and assist you in the process of warranty claims for products purchased / not purchased at Bhinneka, including post-warranty technical support.
                    </h4>
                    <h4 class="font-weight-normal mt-3">* Consultation</h4>
                    <h4 class="font-weight-light mt-2">For those of you who have shopped at Portanusa and want to consult with our technical staff about the product you have purchased, please visit the location of the Portanusa Service Center or contact us at the contact below.
                    </h4>
                    <h4 class="font-weight-normal mt-3">* Take & Delivery Service</h4>
                    <h4 class="font-weight-light mt-2">The service of picking up and delivering products that you want to service at the Portanusa Service Center will certainly make it easy for those of you who might not have time to visit our location directly. This service is available for the Jakarta area.
                    </h4>
                </div>
                <div class="col-sm-9">
                        <h4 class="font-weight-normal" >Portanusa Services Center</h4>
                        <h4 class="font-weight-light">Ruko Permata Regensi Blok D No.37 Jl. H. Kelik Rt. 001/006 Kel. Srengseng, Kec. Kembangan Kota Administrasi Jakarta Barat Daya.
                        </h4>
                        <h4 class="font-weight-normal mt-3" >Working Hours</h4>
                        <p class="font-weight-light">Monday-Friday (08.00-17.00 WIB)
                        </p>
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
