<nav class="navbar navbar-default navbar-fixed-top">
    <div class="brand">
        <!-- <a href="index.html"><img src="{{ asset('img/logo.png') }}" alt="Porta Nusa Logo" class="img-responsive logo"></a> -->
    <a href="index.html"><img src="{{ $logo }}" alt="Porta Nusa Logo" height="25px"></a>
    </div>
    <div class="container-fluid">
        <div class="navbar-btn">
            <button type="button" class="btn-toggle-fullwidth"><i class="lnr lnr-arrow-left-circle"></i></button>
        </div>
        <div id="navbar-menu">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img alt="Avatar" class="img-circle" src="{{ AuthUser::avatar() }}" />
                        <span class="username username-hide-on-mobile">{{ AuthUser::firstName() }}</span>
                        <i class="icon-submenu lnr lnr-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/profile"><i class="lnr lnr-user"></i> <span>My Profile</span></a></li>
                        <li><a href="/logout"><i class="lnr lnr-exit"></i> <span>Logout</span></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>