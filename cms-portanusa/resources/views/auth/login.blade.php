<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Login Porta Nusa</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="/css/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="/css/plugins-md.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="/css/login.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="{{$icon}}" /> </head>
    <!-- END HEAD -->

    <body class=" login">
        <!-- BEGIN LOGIN -->
        <div class="content" style="background-color: rgba(0, 0, 0, 0.8)">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="form-title">Login Porta Nusa</h3>
                    </div>
                    <div class="col-sm-6">
                        <div class="logo">
                            <a href="{{ url('login') }}">
                                <img src="{{ $logo }}" alt="logo" />
                            </a>
                        </div>
                        <!-- BEGIN COPYRIGHT -->
                        <div class="copyright"> {{ date('Y') }} &copy; PT Nusa Network Prakarsa </div>
                        <!-- END COPYRIGHT -->
                    </div>
                    <div class="col-sm-6">
                        <!-- BEGIN LOGIN FORM -->
                        <form class="login-form" action="{{ url('/login') }}" method="POST">
                            {{ csrf_field() }}
                            @if(Session::has('message'))
                            <div class="alert alert-danger">
                                <button class="close" data-close="alert"></button>
                                {{ Session::get('message') }}                    
                            </div>
                            @endif
                            <div class="alert alert-danger display-hide">
                                <button class="close" data-close="alert"></button>
                            </div>
                            <div class="form-group">
                                <label class="control-label visible-ie8 visible-ie9">Email</label>
                                <div class="input-icon">
                                    <i class="fa fa-envelope"></i>
                                    <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" />
                                </div>
                                @if ($errors->has('email'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label visible-ie8 visible-ie9">Password</label>
                                <div class="input-icon">
                                    <i class="fa fa-lock"></i>
                                    <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" />
                                </div>
                                @if ($errors->has('password'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                            {!! captcha_img() !!}<br/><br/>
                            <div class="form-group">
                                <label class="control-label visible-ie8 visible-ie9">Captcha</label>
                                <div class="input-icon">
                                    <i class="fa fa-key"></i>
                                    <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Captcha" name="captcha" />
                                </div>
                                @if ($errors->has('captcha'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('captcha') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn green pull-right"> Login </button>
                            </div>
                            <br/>
                        </form>
                        <!-- END LOGIN FORM -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END LOGIN -->

        <!-- BEGIN CORE PLUGINS -->
        <script src="/js/jquery.min.js" type="text/javascript"></script>
        <script src="/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="/js/js.cookie.min.js" type="text/javascript"></script>
        <script src="/js/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="/js/jquery.blockui.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="/js/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="/js/pages/login.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>
</html>