@extends('layout.master')

@section('title') Master Service @endsection

@section('page_title')
<h1 class="page-title">
    Edit Service
</h1>
@endsection

@section('breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <a href="/">Dashboard</a>
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <a href="/master-service">Service</a>
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <span>Edit</span>
    </li>
</ul>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-form">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-note font-yellow-gold"></i>
                    <span class="caption-subject font-yellow-gold sbold uppercase">{{ $submenu }}</span>
                </div>
                <div class="actions">
                    <a class="btn btn-danger btn-outline btn-circle btn-sm" href="{{ url($menu) }}">
                        <i class="fa fa-undo"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="portlet-body ">
                <!-- BEGIN FORM-->
                <form method="POST" action="{{ url($menu."/".$submenu."/".$service->id) }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    @include('service.form')
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin_styles')
<link rel="stylesheet" type="text/css" href="/plugins/bootstrap-fileinput/bootstrap-fileinput.css">
@endpush

@push('plugin_scripts')
<script type="text/javascript" src="/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script>
@endpush

@push('custom_scripts')
<script type="text/javascript">
    var _validFileExtensions = [".jpg", ".jpeg", ".png"];    
    
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