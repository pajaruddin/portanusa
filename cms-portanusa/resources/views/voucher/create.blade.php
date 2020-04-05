@extends('layout.master')

@section('title') Master Voucher @endsection

@section('page_title')
<h1 class="page-title">
    Tambah Voucher
</h1>
@endsection

@section('breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <a href="/">Dashboard</a>
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <a href="/master-voucher">Voucher</a>
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <span>Add</span>
    </li>
</ul>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-form">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-plus font-green-haze"></i>
                    <span class="caption-subject font-green-haze sbold uppercase">{{ $submenu }}</span>
                </div>
                <div class="actions">
                    <a class="btn btn-danger btn-outline btn-circle btn-sm" href="{{ url($menu) }}">
                        <i class="fa fa-undo"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="portlet-body ">
                <!-- BEGIN FORM-->
                <form method="POST" action="{{ url($menu."/".$submenu) }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    @include('voucher.form')
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin_styles')
<link rel="stylesheet" type="text/css" href="/plugins/bootstrap-fileinput/bootstrap-fileinput.css">
<link rel="stylesheet" type="text/css" href="/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css"/>
@endpush

@push('plugin_scripts')
<script type="text/javascript" src="/plugins/moment/moment.min.js"></script>
<script type="text/javascript" src="/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script>
<script type="text/javascript" src="/plugins/number/number.js"></script>
<script type="text/javascript" src="/plugins/ckeditor/ckeditor.js"></script>
@endpush

@push('custom_scripts')
<script type="text/javascript">
    var _validFileExtensions = [".jpg", ".jpeg", ".png"];   
    CKEDITOR.replace('description_indonesia', {
        removeButtons : 'Underline,Subscript,Superscript,Cut,Undo,Scayt,Link,Image,Maximize,Source,About,Copy,Redo,Paste,PasteText,PasteFromWord,Unlink,Table,Anchor,HorizontalRule,SpecialChar'
    }); 
    
    function validateImage(oInput) {
        console.log("oInput => ", oInput.value);
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
    
    $(document).ready(function() {
        $('.date-start').datetimepicker({
            format: "D MMM YYYY HH:mm:ss",
            ignoreReadonly: true
        });

        $('.date-end').datetimepicker({
            format: "D MMM YYYY HH:mm:ss",
            ignoreReadonly: true
        });

        $(".date-start").on("dp.change", function (e) {
            $('.date-end').data("DateTimePicker").minDate(e.date);
        });

        $(".date-end").on("dp.change", function (e) {
            $('.date-start').data("DateTimePicker").maxDate(e.date);
        });
    });
</script>
@endpush