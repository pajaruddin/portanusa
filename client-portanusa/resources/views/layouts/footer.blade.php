<div class="footer-content mt-5">
    <div class="container">
        <div class="menu-footer pt-5 mb-5">
            <div class="row">
                <div class="col-md-2">
                    <h5>Company</h5>
                    <ul class="mb-2">
                        <li><a href="/inquiry">Inquiry</a></li>
                        <li><a href="/about_us">About Us</a></li>
                    </ul>
                    <h5>Services Informations</h5>
                    <ul>  
                        <li><a href="/return_unit">Returning Of Units</a></li>
                        <li><a href="/serv_after_sales">Service and After Sales</a></li>
                        <li><a href="/term_condition">Terms and Conditions</a></li>
                        <li><a href="/privacy_policy">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h5>Find it Fast</h5>
                    <ul>
                        <li><a href="/videos">Videos</a></li>
                        <li><a href="/article/case_studies">Case Studies</a></li>
                        <li><a href="/article/buying_guide">Buying Guide</a></li>
                        <li><a href="/article/spotlight">Spotlight</a></li>
                        <li><a href="/article/know_how">Know-How</a></li>
                        <li><a href="/download">Download</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Questions? We are here 24/7</h5>
                    <ul>
                        <li><h5><a href="mailto:{{ DisplayMenu::getCompanyInfo()->email }}">{{ DisplayMenu::getCompanyInfo()->email }}</a></h5></li>
                        <li>{{ DisplayMenu::getCompanyInfo()->phone }}</li>
                        <li>
                            {!! DisplayMenu::getCompanyInfo()->address !!}
                        </li>
                    </ul>
                </div>
                <div class="col-md-4 offset-md-1">
                    <ul class="socmed-icon">
                        <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fab fa-pinterest-p"></i></a></li>
                        <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                        <li><a href="#"><i class="fab fa-blogger-b"></i></a></li>
                    </ul>
                    <h5 class="mt-4">Subscribe</h5>
                    <form action="/newsletter/create" method="POST" autocomplete="off">
                        {{ csrf_field() }}
                        <ul>
                            <li>Get the latest news from Portanusa</li>
                            <li>
                                <div class="input-group">
                                    <input type="email" name="email" class="form-control" required placeholder="Your Email Address">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-secondary" type="button"><i class="fa fa-chevron-right"></i></button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
        <div class="service-content">
            <ul>
                <li>
                    <i class="fas fa-award"></i>
                    High Quality
                </li>
                <li>
                    <i class="fas fa-lock"></i>
                    Safe Shopping
                </li>
                <li>
                    <i class="fas fa-sync"></i>
                    30-Day Return
                </li>
                <li>
                    <i class="fas fa-truck"></i>
                    Same Day Shipping
                </li>
            </ul>
        </div>
        <div class="payment-content">
            <h5>Payment Methods : </h5>
            <img src="/images/index_payment_icon_sg.jpg" />
        </div>
        <h6 class="pt-3 pb-3">
            Copyright &copy; 2019 Portanusa All Rights Reserved.
        </h6>
    </div>
</div>