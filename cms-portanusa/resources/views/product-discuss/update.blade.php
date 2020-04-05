@extends('layout.master')

@section('title') Diskusi Produk @endsection

@section('page_title')
<h1 class="page-title">
    Diskusi Produk
</h1>
@endsection

@section('breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <a href="/">Dashboard</a>
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <a href="/master-product-discuss">Diskusi Produk</a>
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <span>Pesan</span>
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
                    <span class="caption-subject font-yellow-gold sbold uppercase">{{ $product_discuss->name }}</span>
                </div>
            </div>
            <div class="portlet-body ">
                <!-- BEGIN FORM-->
                <form method="POST" action="{{ url($menu."/".$submenu."/".$product_discuss->id) }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    @include('product-discuss.form')
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin_styles')
<link rel="stylesheet" type="text/css" href="/css/style.css">
@endpush