@extends('layout.master')

@section('title') Inquiry @endsection

@section('page_title')
<h1 class="page-title">
    Inquiry
</h1>
@endsection

@section('breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <a href="/">Dashboard</a>
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <span>Inquiry</span>
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
                    <span class="caption-subject font-blue-madison sbold uppercase">Daftar Inquiry</span>
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

                <table class="table table-striped table-bordered dt-responsive" id="tbl-inquiry">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No.</th>
                            <th>id</th>
                            <th>Nama</th>
                            <th>Sebagai</th>
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
<link rel="stylesheet" type="text/css" href="/plugins/bootstrap-switch/css/bootstrap-switch.min.css">
<link rel="stylesheet" type="text/css" href="/plugins/datatables/datatables.min.css">
<link rel="stylesheet" type="text/css" href="/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="/css/demo.css">
@endpush

@push('plugin_scripts')
<script type="text/javascript" src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script type="text/javascript" src="/plugins/datatables/datatables.min.js"></script>
<script type="text/javascript" src="/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>
@endpush

@push('custom_scripts')
<script type="text/javascript">
$(function () {
    var table = $('#tbl-inquiry').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{url('/'.'inquiry'.'/data-inquiry')}}",
        columns: [
            {"className": 'row-details', "orderable": false, "data": 0, "defaultContent": '<i class="fa fa-plus-circle"></i>', "searchable": false},
            {"data": "rownum", "searchable": false},
            {"data": "id", "visible":false, "searchable": false},
            {"data": "full_name",},
            {"data": "person_as"},
            {"data": "action", "orderable": false, "searchable": false, "class": "text-center"}
        ],
        autoWidth: false,
        language: {
            url: '{{url("/plugins/datatables/Indonesia.json")}}'
        },
        "fnDrawCallback": function () {
            $(".make-switch").bootstrapSwitch();
        }
    });

    $('#tbl-inquiry tbody').on('click', 'td.row-details', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var data = row.data();
        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{url('/'.$menu.'/detail-inquiry')}}",
                data: {
                    id: data.id
                },
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                    row.child(format(data)).show();
                    tr.addClass('shown');
                }
            });
        }
    });

    $("#tbl-inquiry").on("click", "#delete", function (event, state) {
        var id = $(this).data('id');

        var n = new Noty({
            text: 'Hapus Inquiry ?',
            layout: 'center',
            modal: true,
            buttons: [
                Noty.button('YES', 'btn btn-success', function () {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{url('/'.$menu.'/'.'delete-inquiry')}}",
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

function format(d) {
    return '<table style="padding-left:50px;width:100%" class="table table-bordered table-striped">' +
            '<tr>' +
            '<td style="width:25%">Nama Lengkap</td>' +
            '<td>' + d.full_name + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Nama Perusahaan</td>' +
            '<td>' + d.company_name + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Email</td>' +
            '<td>' + d.email + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>No. Handphone</td>' +
            '<td>' + d.handphone + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>No. Telephone</td>' +
            '<td>' + d.telephone + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Daftar Sebagai</td>' +
            '<td>' + d.person_as + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Provinsi</td>' +
            '<td>' + d.province + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Kota</td>' +
            '<td>' + d.city_name + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Kode Pos</td>' +
            '<td>' + d.postal_zip + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Alamat</td>' +
            '<td>' + d.address + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Pesan</td>' +
            '<td>' + d.message + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Tanggal Daftar</td>' +
            '<td>' + d.created_at + '</td>' +
            '</tr>' +
            '</table>';
}
</script>
@endpush