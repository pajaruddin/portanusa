@extends('layout.master')

@section('title') Newsletter @endsection

@section('page_title')
<h1 class="page-title">
   Export Newsletter
</h1>
@endsection

@section('breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <a href="/">Dashboard</a>
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <span>Export Newsletter</span>
    </li>
</ul>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list font-blue-madison"></i>
                    <span class="caption-subject font-blue-madison sbold uppercase">Export Newsletter</span>
                </div>
                <div class="actions">
                    <a class="btn btn-danger btn-outline btn-circle btn-sm" href="{{ url($menu) }}">
                        <i class="fa fa-undo"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="portlet-body ">
                @if(Session::has('alert-error'))
                <div class="alert alert-danger">
                    <button class="close" data-close="alert"></button>
                    {{ Session::get('alert-error') }}
                </div>
                @endif

                <!-- BEGIN FORM-->
                <form method="POST" action="{{ url($menu."/".$submenu) }}" autocomplete="off" class="form-horizontal" role="form">
                    {{ csrf_field() }}
                    <div class="form-body">
                        @if(Session::has('alert-error'))
                        <div class="alert alert-danger">
                            <button class="close" data-close="alert"></button>
                            {{ Session::get('alert-error') }}
                        </div>
                        @endif

                        <div class="form-group">
                            <label class="col-md-3 control-label">Tanggal Registrasi</label>
                            <div class="col-md-3">
                                <div class="md-radio-list">
                                    <div class="md-radio">
                                        <input id="periode" name="periode" class="md-radiobtn periode" type="radio" value="periode" checked="">
                                        <label for="periode">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> Periode Tertentu
                                        </label>                                        
                                    </div>
                                    <div class="md-radio">
                                        <input id="today" name="periode" class="md-radiobtn periode" type="radio" value="today">
                                        <label for="today">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> Hari Ini
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group input-large date-picker input-daterange" data-date-format="dd M yyyy">
                                    <input type="text" name="date_start" class="form-control" value="">
                                    <span class="input-group-addon"> to </span>
                                    <input type="text" name="date_end" class="form-control" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn green">Ekspor Newsletter</button>
                                <a href="{{ url($menu) }}" class="btn btn-danger"><i class="fa fa-undo"></i> Kembali</a>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin_styles')
<link rel="stylesheet" type="text/css" href="/plugins/bootstrap-select/css/bootstrap-select.css">
<link rel="stylesheet" type="text/css" href="/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
@endpush

@push('plugin_scripts')
<script type="text/javascript" src="/plugins/bootstrap-select/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
@endpush

@push('custom_scripts')
<script type="text/javascript">
$(document).ready(function () {
    $(".bs-select").selectpicker();

    $('.date-picker').datepicker({
        orientation: "left",
        autoclose: true
    });
    
    $(".periode").click(function() {
        var periode = $(this).val();
        if(periode == "today") {
            $('input[name="date_start"]').prop("disabled", true);
            $('input[name="date_end"]').prop("disabled", true);
            
            $('input[name="date_start"]').val('');
            $('input[name="date_end"]').val('');
        } else {
            $('input[name="date_start"]').prop("disabled", false);
            $('input[name="date_end"]').prop("disabled", false);
        }
    });
});
</script>
@endpush