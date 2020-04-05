<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>404 Page Not Found</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #3 for 404 page option 3" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="/css/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="/css/plugins-md.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="/css/error.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> </head>
    <!-- END HEAD -->

    <body class=" page-404-3">
        <div class="page-inner">
            <img src="{{ asset('img/earth.jpg') }}" class="img-responsive" alt=""> 
        </div>
        <div class="container error-404">
            <h1>404</h1>
            <h2>Oops, Halaman Tidak Ditemukan</h2>
            <p>Maaf, halaman yang anda tuju tidak ditemukan. </p>
            <p>
                <a href="{{ url('/') }}" class="btn red btn-outline"> Kembali Ke Beranda </a>
                <br>
            </p>
        </div>
    </body>

</html>