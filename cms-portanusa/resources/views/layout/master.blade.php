<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>@yield('title')</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #1 for blank page layout" name="description" />
        <meta content="" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- VENDOR CSS -->
        <link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="/vendor/linearicons/style.css">
        <!-- MAIN CSS -->
        <link rel="stylesheet" href="/css/main.css">
        <!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
        <link rel="stylesheet" href="/css/demo.css">
        <!-- GOOGLE FONTS -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
        <!-- ICONS -->

        @stack('plugin_styles')
        <link href="/plugins/noty/noty.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="/css/light.min.css" rel="stylesheet" type="text/css"/>
        <link href="/css/custom.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->

    <link rel="shortcut icon" href="{{ $icon }}" />
    <!-- END HEAD -->

    <body>
        <!-- WRAPPER -->
	    <div id="wrapper">
            <!-- NAVBAR -->
            @include('layout.header')
            <!-- END NAVBAR -->

            <!-- LEFT SIDEBAR -->
            @include('layout.sidebar')
            <!-- END LEFT SIDEBAR -->

            <!-- MAIN -->
		    <div class="main">
                <!-- MAIN CONTENT -->
                <div class="main-content">
                    <div class="container-fluid">
                        {{-- <h3 class="page-title">Icons</h3> --}}
                        <div class="panel panel-headline demo-icons">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    @yield('page_title')
                                </h3>
                                <div class="page-bar">
                                    @yield('breadcrumb')
                                </div>
                            </div>
                            <div class="panel-body">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END MAIN CONTENT -->
            </div>
            <!-- END MAIN -->
            <div class="clearfix"></div>
            @include('layout.footer')
        </div>
        <!-- END WRAPPER -->
        
        <!-- BEGIN CORE PLUGINS -->
        <script src="/vendor/jquery/jquery.min.js"></script>
        <script src="/vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="/vendor/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="/scripts/klorofil-common.js"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        @stack('plugin_scripts')
        <script src="/plugins/noty/noty.js" type="text/javascript"></script>        
        <!-- END PAGE LEVEL PLUGINS -->
        @stack('custom_scripts')
    </body>

</html>