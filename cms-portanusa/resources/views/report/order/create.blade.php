@extends('layout.master')

@section('title') Report Penjualan @endsection

@section('page_title')
<h1 class="page-title">
    Report Penjualan
</h1>
@endsection

@section('breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <a href="/">Dashboard</a>
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <span>Report Penjualan</span>
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
                    <span class="caption-subject font-green-haze sbold uppercase">Report Penjualan</span>
                </div>
                <div class="actions">
                    <a class="btn btn-danger btn-outline btn-circle btn-sm" href="/">
                        <i class="fa fa-undo"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="portlet-body ">
                <!-- BEGIN FORM-->
                <form method="POST" action="{{ url($menu) }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    @include('report.order.form')
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin_styles')
<link rel="stylesheet" type="text/css" href="/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css"/>
@endpush

@push('plugin_scripts')
<script type="text/javascript" src="/plugins/bootstrap-datetimepicker/moment.js"></script>
<script type="text/javascript" src="/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>
<script>
    $(document).ready(function() {
        $('.date-start').datetimepicker({
            format: "MMMM YYYY",
            ignoreReadonly: true
        });

        $(".date-start").on("dp.change", function (e) {
            console.log('.date-start', $('#export_date').val());
        });
    });
</script>
@endpush