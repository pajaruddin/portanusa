@extends('layout.master')

@section('title') Master Sale Event @endsection

@section('page_title')
<h1 class="page-title">
    Tambah Sale Event
</h1>
@endsection

@section('breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <a href="/">Dashboard</a>
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <a href="/master-sale-event">Sale Event</a>
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
                    @include('sale-event.form')
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
@endpush

@push('custom_scripts')
<script type="text/javascript">
var counter_product = 0;
var product_array = [];

var _validFileExtensions = [".jpg", ".png"];    
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

function formatRepo(repo) {
    if (repo.loading)
        return repo.text;

    var markup = "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__avatar'><img src='" + repo.image + "' /></div>" +
            "<div class='select2-result-repository__meta'>" +
            "<div class='select2-result-repository__title'>" + repo.subject + "</div>";

    if (repo.full_name) {
        markup += "<div class='select2-result-repository__description'>" + repo.full_name + "</div>";
    }

    markup += "<div class='select2-result-repository__statistics' style='margin-top: 5px;'>" +
            "<div class='select2-result-repository__forks'><span class='label label-success label-sm'>" + repo.type_status + "</span></div>" +
            // "<div class='select2-result-repository__stargazers'><span class='label label-warning label-sm'>" + repo.availability_status + "</span></div>" +
            "<div class='select2-result-repository__watchers'><span class='label label-danger label-sm'>" + repo.stock_status + "</span></div>" +
            "</div>" +
            "</div></div>";

    return markup;
}

function formatRepoSelection(repo) {
    return repo.full_name || repo.text;
}

Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};

function deleteProductItemRow(row_id) {
	var product_id = parseInt($("#product_id_" + row_id).val());
    product_array.remove(product_id)
    $("#row_sl_" + row_id).remove();
}

$(document).ready(function () {
    $(".select-product").select2({
        placeholder: "-- Pilih Produk --",
        ajax: {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{url('/'.$menu.'/search-product')}}",
            dataType: 'json',
            delay: 250,
            type: 'POST',
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data, page) {
                return {
                    results: data.items
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        minimumInputLength: 1,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
    });

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

    $("#add-product").click(function () {
        var product_data = $(".select-product").select2('data');
        var product_id = parseInt($(".select-product").val());
        if (product_id != null) {
            if (product_array.indexOf(product_id) == -1) {
            	product_array.push(product_id);

                var html = "<tr id='row_sl_" + counter_product + "'>";
                html += "<input type='hidden' id='sale_event_product_" + counter_product + "' name='product_sale_event[" + counter_product + "][id]' value=''>";
                html += "<td>";
                html += "<input type='hidden' id='product_id_" + counter_product + "' name='product_sale_event[" + counter_product + "][product_id]' value='" + product_id + "'>";
                html += product_data[0].full_name;
                html += "</td>";
                html += "<td>" + CurrencyFormat(product_data[0].price) + "</td>";
                html += "<td>";
                html += "<input type='text' class='form-control' name='product_sale_event[" + counter_product + "][discount]' onkeypress='return decimalValue(event);' value=''>";
                html += "</td>";
                html += "<td class='text-center'>";
                html += "<button type='button' class='btn btn-danger' onclick='deleteProductItemRow(" + counter_product + ")'><i class='fa fa-close'></i></button>";
                html += "</td>";
                html += "</tr>";
                $("#tbl-product").append(html);
                $('.select-product').val(null).trigger('change');
                counter_product = counter_product + 1;
            } else {
                new Noty({
                    type: 'error',
                    text: 'Produk sudah dipilih',
                    layout: 'center',
                    timeout: 2000,
                    modal: true
                }).show();
            }
        } else {
            new Noty({
                type: 'error',
                text: 'Silahkan pilih produk terlebih dahulu',
                layout: 'center',
                timeout: 2000,
                modal: true
            }).show();
        }
    });

    $(".make-switch").bootstrapSwitch();
});
</script>
@endpush