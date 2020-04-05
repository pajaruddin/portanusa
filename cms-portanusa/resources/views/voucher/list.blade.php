@extends('layout.master')

@section('title') Master Voucher @endsection

@section('page_title')
<h1 class="page-title">
    Master Voucher
</h1>
@endsection

@section('breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <a href="/">Dashboard</a>
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <span>Brand</span>
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
                    <span class="caption-subject font-blue-madison sbold uppercase">Daftar Voucher</span>
                </div>
                <div class="actions">
                    <a class="btn green-haze btn-outline btn-circle btn-sm" href="/master-voucher/add-voucher">
                        Tambah Voucher
                    </a>
                </div>
            </div>
            <div class="portlet-body ">
                @if(Session::has('alert-success'))
                <div class="alert alert-success">
                    <button class="close" data-close="alert"></button>
                    {{ Session::get('alert-success') }}                    
                </div>
                @endif

                @if(Session::has('alert-error'))
                <div class="alert alert-danger">
                    <button class="close" data-close="alert"></button>
                    {{ Session::get('alert-error') }}                    
                </div>
                @endif                

                <table class="table table-striped table-bordered dt-responsive" id="tbl-voucher">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>id</th>
                            <th>Nama</th>
                            <th>code</th>
                            <th>Tanggal Awal</th>
                            <th>Tanggal Akhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin_styles')
<link rel="stylesheet" type="text/css" href="/plugins/datatables/datatables.min.css">
<link rel="stylesheet" type="text/css" href="/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="/css/demo.css">
@endpush

@push('plugin_scripts')
<script type="text/javascript" src="/plugins/datatables/datatables.min.js"></script>
<script type="text/javascript" src="/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>
@endpush

@push('custom_scripts')
<script type="text/javascript">

$(function () {
    var table = $('#tbl-voucher').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{url('/'.'master-voucher'.'/data-voucher')}}",
        columns: [
            {"data": "rownum", "searchable": false},
            {"data": "id", "visible":false, "searchable": false},
            {"data": "name"},
            {"data": "code"},
            {"data": "date_start", "class":"text-center"},
            {"data": "date_end", "class":"text-center"},
            {"data": "action", "orderable": false, "searchable": false, "class": "text-center"}
        ],
        autoWidth: false,
        language: {
            url: '{{url("/plugins/datatables/Indonesia.json")}}'
        },
    });

    $("#tbl-voucher").on("click", "#delete", function (event, state) {
        var id = $(this).data('id');

        var n = new Noty({
            text: 'Hapus Voucher ?',
            layout: 'center',
            modal: true,
            buttons: [
                Noty.button('YES', 'btn btn-success', function () {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{url('/'.$menu.'/'.'delete-voucher')}}",
                        data: {
                            id: id
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: function (data) {
                            if (data.status == "error") {
                                n.close();
                                new Noty({
                                    type: 'error',
                                    text: data.message,
                                    layout: 'center',
                                    timeout: 2000,
                                    modal: true
                                }).show();
                            } else {
                                location.reload();
                            }
                        }
                    });
                }, {id: 'button1', 'data-status': 'ok'}),

                Noty.button('NO', 'btn btn-error', function () {
                    n.close();
                })
            ]
        }).show();
    });
});
</script>
@endpush