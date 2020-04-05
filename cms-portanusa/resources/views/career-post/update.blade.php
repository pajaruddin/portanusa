@extends('layout.master')

@section('title') Karir @endsection

@section('page_title')
<h1 class="page-title">
    Edit Karir
</h1>
@endsection

@section('breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <a href="/">Dashboard</a>
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <a href="/master-article">Karir</a>
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
                <form method="POST" action="{{ url($menu."/".$submenu."/".$career->id) }}" autocomplete="off" enctype="multipart/form-data" class="form-horizontal" role="form">
                    {{ csrf_field() }}
                    @include('career-post.form')
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin_styles')
<link rel="stylesheet" type="text/css" href="/plugins/bootstrap-switch/css/bootstrap-switch.min.css">
<link rel="stylesheet" type="text/css" href="/plugins/select2/css/select2.min.css">
<link rel="stylesheet" type="text/css" href="/plugins/select2/css/select2-bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" type="text/css" href="/plugins/bootstrap-fileinput/bootstrap-fileinput.css">
@endpush

@push('plugin_scripts')
<script type="text/javascript" src="/plugins/moment/moment.min.js"></script>
<script type="text/javascript" src="/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script type="text/javascript" src="/plugins/select2/js/select2.full.min.js"></script>
<script type="text/javascript" src="/plugins/number/number.js"></script>
<script type="text/javascript" src="/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script>
<script type="text/javascript" src="/plugins/ckeditor/ckeditor.js"></script>
@endpush

@push('custom_scripts')
<script type="text/javascript">
    CKEDITOR.replace('job_description', {
        removeButtons : 'Underline,Subscript,Superscript,Cut,Undo,Scayt,Link,Image,Maximize,Source,About,Copy,Redo,Paste,PasteText,PasteFromWord,Unlink,Table,Anchor,HorizontalRule,SpecialChar'
    });
    CKEDITOR.replace('job_requirement', {
        removeButtons : 'Underline,Subscript,Superscript,Cut,Undo,Scayt,Link,Image,Maximize,Source,About,Copy,Redo,Paste,PasteText,PasteFromWord,Unlink,Table,Anchor,HorizontalRule,SpecialChar'
    });

    $(document).ready(function() {
        $(".make-switch").bootstrapSwitch();

        $('.date-start').datetimepicker({
            format: "D MMM YYYY"
        });

        $('.date-end').datetimepicker({
            format: "D MMM YYYY"
        });

    });
</script>
@endpush