<div class="navbar-content">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3">
                <div class="navbar-content-logo">
                    <a href="/">
                        <img src="<?= DisplayMenu::getLogo() ?>" />
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="header_search_content">
                    <div class="header_search_form_container">
                        <form action="/search/result" class="header_search_form clearfix" method="GET" role="form">
                            <input type="text" name="search" required="required" class="header_search_input w-100" placeholder="Search for products...">
                            <button type="submit" class="header_search_button trans_300" value="Submit">Search</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="navbar-content-menu">
                    <ul>
                        @if(!Auth::check())
                        <li><a href="javascript:;" data-toggle="modal" data-target="#modalRegister">Register</a></li>
                        <li><a href="javascript:;" data-toggle="modal" data-target="#modalSignIn">Sign In</a></li>
                        @else
                        <li><a href="/account">Hai {{ Auth::user()->first_name }}</a></li>
                        <li><a href="/logout">Logout</a></li>
                        @endif
                    </ul>
                    <ul class="float-right">
                        <li>
                            <a href="/cart" class="position-relative">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="badge badge-pill badge-primary cart_total">{{ DisplayCart::countCart() }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>