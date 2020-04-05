@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
    <div class="footer-content mb-5">
    <div class="banner position-relative">
        <img src="/images/banner_2_background.jpg" class="img-fluid" />
        <div class="overlay position-absolute h-100 w-100 d-flex justify-content-center align-items-center">
            <h1>Privacy Policy</h1>
        </div>
    </div>
    <div class="container">
        <div class="about-us-content mt-4 mb-4">
            <div class="row mt-5">
                <div class="col-sm-12">
                    <h4 class="font-weight-light">All personal information that you provide to Portanusa will only be used and protected by Portanusa. Any information you provide is limited to the purpose of the process relating to Portanusa and without any other purpose. We may change this Privacy Policy from time to time by making deductions or adding provisions to this page. Changes to this policy will be announced on the Portanusa website or through addresses from other media that you have provided to us. It is recommended that you read this Privacy Policy periodically to learn about the latest changes.    
                    </h4>
                </div>
                <div class="col-sm-10 offset-sm-1 mt-2">
                    <h3 class="font-weight-normal">Registration</h3>
                    <h4 class="font-weight-light mt-2">
                        Everyone can access and view material on our site, but you are required to register (Register) to be able to access information and services on this site in full. Features like ordering goods can only be done if you are already a member of Portanusa. To register on the Portanusa website, we need information like the following :
                    </h4>
                    <ol class="offset-sm-1">
                        <li>Full Name</li>
                        <li>Last Name</li>
                        <li>Phone Number</li>
                        <li>Email</li>
                    </ol>
                    <h4 class="font-weight-light mt-2">
                        We assume that the information you provide now and the changes you make in the future are accurate and correct. If the information and changes provided are proven to be incorrect, then we are not responsible for any consequences that may occur in connection with the provision of information and changes that are not true.
                    </h4>
                    <h3 class="font-weight-normal">Use of Information We Collect</h3>
                    <h4 class="font-weight-light mt-2">All personal information that we collect from you voluntarily at the time of registration will be stored, used and protected in accordance with this data protection law and privacy policy. We collect your information to provide the best products and services for you. Your information will be used to :
                    </h4>
                    <ol class="offset-sm-1">
                        <li>Speed ​​up your shopping process</li>
                        <li>The importance of order confirmation</li>
                        <li>The importance of shipping the order</li>
                        <li>Simplify payment transactions</li>
                        <li>Collecting transaction data between you and Portanusa</li>
                        <li>Contacting you for market research or site development purposes, so that we can improve our services, and / or to customize the site to provide the services and products that interest you most</li>
                        <li>To administer a contest, promotion, survey, market research, focus group or other and to provide you with relevant products or services (for example: to send a prize to you if you have won a contest organized by Portanusa)</li>
                        <li>Administrative interests of Portanusa</li>
                    </ol>
                    <h3 class="font-weight-normal">Promotions and Special Events</h3>
                    <h4 class="font-weight-light mt-2">We often hold promotions and special events organized by third parties or our partners. If this information will be shared with third parties, we will notify you at the time of data collection.
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
