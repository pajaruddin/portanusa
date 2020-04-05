@extends('layout.master')

@section('title') Profil {{ Auth::user()->first_name." ".Auth::user()->last_name }} @endsection

@section('page_title')
<h1 class="page-title">
    Profil {{ Auth::user()->first_name." ".Auth::user()->last_name }}
</h1>
@endsection

@section('breadcrumb')
{{-- <ul class="page-breadcrumb">
    <li>
        
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <span>Profil</span>
    </li>
</ul> --}}
@endsection

@section('content')
@if(Session::has('alert-error'))
<div class="alert alert-danger">
    <button class="close" data-close="alert"></button>
    {{ Session::get('alert-error') }}
</div>
@endif

@if(Session::has('alert-success'))
<div class="alert alert-success">
    <button class="close" data-close="alert"></button>
    {{ Session::get('alert-success') }}
</div>
@endif

<div class="row profile-account">
        <div class="col-md-3">
            <ul class="ver-inline-menu tabbable margin-bottom-10">
                <li class="{{ $tab == "personal" ? "active" : "" }}">
                    <a data-toggle="tab" href="#personal-info">
                        <i class="fa fa-cog"></i>Informasi Akun
                    </a>                
                </li>
                {{-- <li class="{{ $tab == "avatar" ? "active" : "" }}">
                    <a data-toggle="tab" href="#change-avatar">
                        <i class="fa fa-picture-o"></i>Ubah Avatar
                    </a>
                </li> --}}
                <li class="{{ $tab == "password" ? "active" : "" }}">
                    <a data-toggle="tab" href="#change-password">
                        <i class="fa fa-lock"></i>Ubah Password
                    </a>
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="tab-content">
                <div id="personal-info" class="tab-pane {{ $tab == "personal" ? "active" : "" }}">
                    @include('profile.tab.personal')
                </div>
                {{-- <div id="change-avatar" class="tab-pane {{ $tab == "avatar" ? "active" : "" }}">
                    @include('profile.tab.avatar')
                </div> --}}
                <div id="change-password" class="tab-pane {{ $tab == "password" ? "active" : "" }}">
                    @include('profile.tab.password')
                </div>
            </div>
        </div>
        <!--end col-md-9-->
    </div>
@endsection

@push('plugin_styles')
<link rel="stylesheet" type="text/css" href="/plugins/bootstrap-fileinput/bootstrap-fileinput.css">
@endpush

@push('plugin_scripts')
<script type="text/javascript" src="/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script>
<script type="text/javascript" src="/plugins/number/number.js"></script>
@endpush

@push('custom_scripts')
<script type="text/javascript">
    var _validFileExtensions = [".jpg", ".png"];
    
    function validateImage(oInput) {
        if (oInput.type == "file") {
            var sFileName = oInput.value;
            if (sFileName.length > 0) {
                var blnValid = false;
                for (var j = 0; j < _validFileExtensions.length; j++) {
                    var sCurExtension = _validFileExtensions[j];
                    if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                        blnValid = true;
                        break;
                    }
                }
                if (!blnValid) {
                    new Noty({
                        type: 'error',
                        text: 'Format file gambar tidak diizinkan',
                        layout: 'center',
                        timeout: 2000,
                        modal: true
                    }).show();
                    oInput.value = "";
                    return false;
                }
            }
        }
        return true;
    }
</script>
@endpush