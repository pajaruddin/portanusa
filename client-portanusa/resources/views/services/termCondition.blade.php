@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
    <div class="footer-content mb-5">
    <div class="banner position-relative">
        <img src="/images/banner_2_background.jpg" class="img-fluid" />
        <div class="overlay position-absolute h-100 w-100 d-flex justify-content-center align-items-center">
            <h1>Terms and Conditions</h1>
        </div>
    </div>
    <div class="container">
        <div class="about-us-content mt-4 mb-4">
            <h3 class="font-weight-normal text-center">Ready To Serve You</h3>
            <div class="row mt-5">
                <div class="col-sm-12">
                    <h4 class="font-weight-light ">
                        You must read, understand, accept and agree to all terms and conditions in this agreement before using the application and / or accepting the content contained in it. By accessing or using the Portanusa website, users are deemed to have understood and agreed to all contents in the terms and conditions below. Terms and conditions can be changed or updated at any time without prior notice. Changes to the terms and conditions will take effect immediately after they are posted on the Portanusa website. If users object to the terms and conditions that we propose in this Agreement, we recommend not to use this site.    
                    </h4>
                    <div class="col-sm-12">
                            <ol>
                                <li><h4 class="mt-3">Site Terms of Use</h4>
                                <h4 class="font-weight-light">When visiting and using the Portanusa website, including all its features and services, each user is required to comply with the following site user conditions:</h4>
                                <div class="col-sm-12">
                                    <ol>
                                        <li>Minimum users aged 18 years, have been married, or who already have legally recognized requirements are included in the category of users who are adults / apart from child protection laws.</li>
                                        <li>Access to this site is only permitted for the purposes and purposes of shopping and information related to this site's services.</li>
                                        <li>Users are not permitted to reproduce, distribute, display, sell, rent, transmit, make derivative works from, translate, modify, reverse engineer, disassemble, decompile or exploit the Portanusa site.</li>
                                        <li>Users are not permitted to load and publish content that :
                                            <ul>
                                                <li>A. <i> copyright, patent, trademark, service mark, trade secret, or other ownership rights.</i></li>
                                                <li>B. <i>Threatening, obscene, indecent, pornographic or can lead to all obligations of Indonesian civil or criminal law or international law.</i></li>
                                                <li>C. <i>Contains bugs, viruses, worms, trap doors, Trojan horses or other malicious code and properties.</i></li>
                                            </ul>
                                        </li>
                                        <li>
                                            The product offered is not a product made by Portanusa, but from a vendor.
                                        </li>
                                    </ol>
                                </div>
                                </li>
                                <li class="mt-2">
                                    <h4>Intellectual Property Rights</h4>
                                    <h4 class="font-weight-light">PT. Porta Nusa Indonesia is the sole owner or legal holder of all rights to the site and the content of the Portanusa site. All content contained on the Portanusa website includes intellectual property protected by copyright laws and other laws protecting intellectual property that apply worldwide. All proprietary and intellectual property rights to the Portanusa site and its contents remain with us, its affiliates or licensors. Contents of the Portanusa site. All rights not included in this agreement or by us are hereby protected by law including :</h4>
                                    <div class="col-sm-12">
                                        <ol>
                                            <li>All software ownership.</li>
                                            <li>The Portanusa name, related icons and logos are registered trademarks in various jurisdictions and are protected by copyright, trademark or other intellectual property rights. It is strictly forbidden to use, change or install the above brands for personal use and to describe Portanusa.</li>
                                        </ol>
                                    </div>
                                </li>
                                <li><h4 class="mt-2">Links</h4>
                                    <h4 class="font-weight-light">This site may contain internet links to other sites that are owned and operated by third parties. You need to know, that we are not responsible for the operation or content located on the site.
                                    </h4>
                                </li>
                                <li>
                                    <h4 class="mt-2">Price</h4>
                                    <h4 class="font-weight-light">If in certain circumstances, there is a price error or information about a particular product caused by typing (typo) or a price error and information originating from the supplier, Portanusa has the right to refuse or cancel orders that use the wrong price including the order that was paid. If the order has been paid by credit card, and your credit card has been charged for the purchase, then we will refund according to the amount paid.
                                    </h4>
                                </li>
                                <li>
                                    <h4 class="mt-2">Product Thesis</h4>
                                    <h4 class="font-weight-light">Portanusa selalu berusaha untuk memberikan deskripsi produk seakurat mungkin. Tetapi kami tidak dapat 100% menjamin bahwa seluruh deskripsi atau konten yang terdapat di dalam website adalah akurat, lengkap, terbaru, atau bebas error. Jika produk yang ditawarkan oleh Portanusa tidak sesuai dengan yang tertera dalam deskripsi produk, maka Anda dapat mengembalikannya dalam keadaan belum terpakai / buka segel.
                                    </h4>
                                </li>
                                <li>
                                        <h4 class="mt-2">Availability of Goods</h4>
                                        <h4 class="font-weight-light">In order to maintain a competitive price, it is very difficult for us to always provide (ready stock) every product that we display at Bhinneka. Our staff will be happy to reconfirm if there are some products that need to be ordered and the length of time to order. We believe only this way, we can still maintain the best selling prices for our customers.
                                        </h4>
                                </li>
                                <li>
                                        <h4 class="mt-2">Changes and Term of Regulations</h4>
                                        <h4 class="font-weight-light">In order to maintain a competitive price, it is very difficult for us to always provide (ready stock) every product that we display at Bhinneka. Our staff will be happy to reconfirm if there are some products that need to be ordered and the length of time to order. We believe only this way, we can still maintain the best selling prices for our customers.
                                        </h4>
                                </li>
                            </ol>
                    </div>
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
