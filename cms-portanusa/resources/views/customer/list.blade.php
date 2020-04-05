@extends('layout.master')

@section('title') Master Pelanggan @endsection

@section('page_title')
<h1 class="page-title">
    Master Pelanggan
</h1>
@endsection

@section('breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <a href="/">Dashboard</a>
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <span>Pelanggan</span>
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
                    <span class="caption-subject font-blue-madison sbold uppercase">Daftar Pelanggan</span>
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

                <table class="table table-striped table-bordered dt-responsive" id="tbl-customer">
                    <thead>
                        <tr>
                            <th>#</th>
                            {{-- <th>No.</th> --}}
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Group</th>
                            <th>Aktif</th>
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
    var table = $('#tbl-customer').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{url('/'.'master-customer'.'/data-customer')}}",
        columns: [
            {"className": 'row-details', "orderable": false, "data": 0, "defaultContent": '<i class="fa fa-plus-circle"></i>', "searchable": false},
            // {"data": "rownum", "searchable": false},
            {"data": "first_name", "orderable": false, "searchable": false},
            {"data": "email"},
            {"data": "display_name"},
            {"data": "active", "class": "text-center", "orderable": false},
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

    $('#tbl-customer').on('switchChange.bootstrapSwitch', 'input[name="active"]', function (event, state) {
        var id = $(this).data('id');
        var active = "";
        if (state) {
            active = 1;
        } else {
            active = 0;
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{url('/'.'master-customer'.'/active-master-customer')}}",
            data: {
                active: active,
                id: id
            },
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.status == "success") {
                    new Noty({
                        type: 'success',
                        text: 'Akun Berhasil Terverifikasi',
                        layout: 'center',
                        timeout: 1000,
                        modal: true
                    }).show();

                    setTimeout(function(){
                        location.reload();
                    }, 3000);
                }
            }
        });
    });

    $('#tbl-customer tbody').on('click', 'td.row-details', function () {
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
                url: "{{url('/'.$menu.'/detail-customer')}}",
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

    $("#tbl-customer").on("click", "#delete", function (event, state) {
        var id = $(this).data('id');

        var n = new Noty({
            text: 'Hapus Pelanggan ?',
            layout: 'center',
            modal: true,
            buttons: [
                Noty.button('YES', 'btn btn-success', function () {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{url('/'.$menu.'/'.'delete-customer')}}",
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
            '<td style="width:25%">Nama Depan</td>' +
            '<td>' + d.first_name + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Nama Belakang</td>' +
            '<td>' + d.last_name + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Email</td>' +
            '<td>' + d.email + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Birthday</td>' +
            '<td>' + d.birthday + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>No. Handphone</td>' +
            '<td>' + d.handphone + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Grup Akses</td>' +
            '<td>' + d.group + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Gender</td>' +
            '<td>' + d.gender + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Nama Perusahaan</td>' +
            '<td>' + d.company_name + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Alamat</td>' +
            '<td>' + d.address + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Status Akun</td>' +
            '<td>' + d.active + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Terakhir Login</td>' +
            '<td>' + d.last_login_at + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Dibuat</td>' +
            '<td>' + d.created_at + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Diubah</td>' +
            '<td>' + d.updated_at + '</td>' +
            '</tr>' +
            '</table>';
}
</script>
@endpush