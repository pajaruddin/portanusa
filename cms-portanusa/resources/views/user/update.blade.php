@extends('layout.master')

@section('title') Master Pengguna @endsection

@section('page_title')
<h1 class="page-title">
    Edit Pengguna
</h1>
@endsection

@section('breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <a href="/">Dashboard</a>
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <a href="/master-user">Pengguna</a>
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
                <form method="POST" action="{{ url($menu."/".$submenu."/".$user_access->id) }}" autocomplete="off" class="form-horizontal" role="form">
                    {{ csrf_field() }}
                    @include('user.form')
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin_scripts')
<script type="text/javascript" src="/plugins/number/number.js"></script>
@endpush