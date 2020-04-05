@extends('layout.master')

@section('title') Detail Order @endsection

@section('page_title')
<h1 class="page-title">
    Detail Order
</h1>
@endsection

@section('breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <a href="/">Dashboard</a>
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <a href="/master-order">Order</a>
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <span>Detail</span>
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
                    <span class="caption-subject font-green-haze sbold uppercase">No Invoice : {{ $order->invoice_no }}</span>
                </div>
                <div class="actions">
                    <a class="btn btn-danger btn-outline btn-circle btn-sm" href="{{ url($menu) }}">
                        <i class="fa fa-undo"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="portlet-body ">
                <form method="POST" action="{{ url($menu."/".$submenu."/".$order->id) }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
               <!-- MAIN CONTENT -->
                <div class="main-content">
                    <div class="container-fluid">
                        <div class="panel panel-profile">
                            <div class="clearfix">
                                <!-- LEFT COLUMN -->
                                <div class="profile-left">
                                    <!-- PROFILE HEADER -->
                                    <div class="profile-header">
                                        <div class="overlay"></div>
                                        <div class="profile-main">
                                            <img src="/img/avatar.jpeg" class="img-circle" alt="Avatar">
                                            <h3 class="name">{{ $customer->first_name }}</h3>
                                            <span class="online-status status-available">Available</span>
                                        </div>
                                    </div>
                                    <!-- END PROFILE HEADER -->
                                    <!-- PROFILE DETAIL -->
                                    <div class="profile-detail">
                                        <div class="profile-info">
                                            <h4 class="heading">Profil Pelanggan</h4>
                                            <ul class="list-unstyled list-justify">
                                                <li>Nama <span>{{ $customer->first_name . ' ' . $customer->last_name }}</span></li>
                                                <li>No Identitas <span>{{ $customer->identity_no }}</span></li>
                                                <li>Jenis Kelamin 
                                                    <span>
                                                        @if ($customer->gender != null)
                                                            {{ $customer->gender }}
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </li>
                                                <li>Tanngal Lahir 
                                                    <span>
                                                        @if ($customer->birthday != null)
                                                            {{ date('dd MMMM Y', strtotime($customer->birthday)) }}
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </li>
                                                <li>Handphone <span>{{ $customer->handphone }}</span></li>
                                                <li>Email <span>{{ $customer->email }}</span></li>
                                                <li>Status Pelanggan <span>{{ $customer->person_as }}</span></li>
                                                <li>Nama Perusahaan 
                                                    <span>
                                                        @if ($customer->company_name != null)
                                                            {{ $customer->company_name }}
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </li>
                                                <li>Alamat :</li>
                                                <li>
                                                    @if ($customer->address != null)
                                                        {{ $customer->address }}
                                                    @else
                                                        -
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- END PROFILE DETAIL -->
                                </div>
                                <!-- END LEFT COLUMN -->
                                <!-- RIGHT COLUMN -->
                                <div class="profile-right">
                                    <h4 class="heading">Alamat Pengiriman</h4>
                                    <!-- AWARDS -->
                                    <div class="awards">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <td>Tanggal Order</td>
                                                            <td>:</td>
                                                            <td>{{ date('d/m/Y H:i:s', strtotime($order->created_at)) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Nama</td>
                                                            <td>:</td>
                                                            <td>{{ $order->shipping_name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Hanphone</td>
                                                            <td>:</td>
                                                            <td>{{ $order->shipping_phone }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Provinsi</td>
                                                            <td>:</td>
                                                            <td>{{ $order->shipping_province }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Kota</td>
                                                            <td>:</td>
                                                            <td>{{ $order->shipping_city }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Kode Pos</td>
                                                            <td>:</td>
                                                            <td>{{ $order->shipping_postal_code }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Alamat</td>
                                                            <td>:</td>
                                                            <td>{{ $order->shipping_address }}</td>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END AWARDS -->
                                    <h4 class="heading">Detail Produk</h4>
                                    <!-- Detail Produk -->
                                    <div class="awards">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <table class="table table-condensed table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Produk</th>
                                                            <th>Quantity</th>
                                                            <th>Harga</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>  
                                                        <?php $no = 1 ?>
                                                        @if(count($order_products) > 0)
                                                            @foreach ($order_products as $product)
                                                                <tr>
                                                                    <td>{{ $no }}</td>
                                                                    <td>{{ $product->name }}</td>
                                                                    <td>{{ $product->quantity }}</td>
                                                                    <td>{{ number_format($product->price, 0, ",", ".") }}</td>
                                                                </tr>
                                                            <?php $no++ ?>
                                                            @endforeach
                                                        @endif
                                                        <tr>
                                                            <td colspan="3" class="text-right" style="font-weight:bold">Jumlah</td>
                                                            <td>{{ number_format($price, 0, ",", ".") }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="text-right" style="font-weight:bold">Diskon Voucer</td>
                                                            <td>{{ number_format($order->discount_price, 0, ",", ".") }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="text-right" style="font-weight:bold">Total Harga</td>
                                                            <td>{{ number_format($order->total_price, 0, ",", ".") }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="heading">Status Order</h4>
                                    <div class="awards">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                <span class="label label-warning label-lg" style="font-weight:bold;">{{ $order->status_order }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="heading">Bukti Transfer</h4>
                                    <!-- Detail Produk -->
                                    <div class="awards">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                @if ($order->transfer_image != null)
                                                    <img src="{{ $domain_asset }}/{{ $transfer_path }}/{{ $order->transfer_image }}" class="img img-responsive">
                                                @else
                                                    <h4 class="heading" style="text-align:left; border-bottom:none">Tidak ada bukti transfer</h4>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="heading">Update Status Order</h4>
                                    <div class="awards">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Status
                                                        <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-5">
                                                        <select class="bs-select form-control" id="status" data-live-search="true" data-size="5" name="status">
                                                            <option value="">Pilih Status</option>
                                                            @if($order->status === 2)
                                                                <option value="1">Transaksi Dibatalkan</option>
                                                                <option value="3">Pembayaran Telah di Terima</option>
                                                            @elseif($order->status === 3)
                                                                <option value="4">Barang Sedang Dalam Pengiriman</option>
                                                            @endif
                                                        </select>
                                                        <span class="text-danger" id="status-error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-sm-12" id="awb_number" style="display: none">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">No Resi
                                                        <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="awb_number">
                                                    </div>
                                                </div>
                                            </div>
                                            @if($order->tax_invoice == 'T')
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        @php $name_file = !empty($order->file_tax) ? $order->file_tax : ""; @endphp
                                                        <label class="col-md-3 control-label">Faktur
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-9" style="text-align: left !important">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail" style="border: none !important">
                                                                    {{ $name_file }}
                                                                </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail" style="border: none !important"> </div>
                                                                <div>
                                                                    <span class="btn default btn-file">
                                                                        @if (!empty($order->file_tax))
                                                                        <span class="fileinput-new"> Ubah File </span>                            
                                                                        @else
                                                                        <span class="fileinput-new"> Pilih File </span>                            
                                                                        @endif
                                                                        <span class="fileinput-exists"> Ubah File</span>
                                                                        <input type="file" name="file_tax" value="{{ (!empty($order)) ? $order->file_tax : old('file_tax') }}" onchange="validateImage(this);">
                                                                    </span>
                                                                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput">Batalkan</a>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix margin-top-10">
                                                                <span class="label label-danger">NOTE!</span> Format File PDF.
                                                            </div>
                                                            @if ($errors->has('file_tax'))
                                                            <div class="clearfix margin-top-10">
                                                                <span class="text-danger">
                                                                    {{ $errors->first('file_tax') }}
                                                                </span>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- END Detail Produk -->
                                </div>
                                <!-- END RIGHT COLUMN -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END MAIN CONTENT -->
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green">Simpan</button>
                            <a href="/" class="btn btn-danger"><i class="fa fa-undo"></i> Kembali</a>
                        </div>
                    </div>
                </div> 
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin_styles')
<link rel="stylesheet" type="text/css" href="/plugins/bootstrap-select/css/bootstrap-select.css">
<link rel="stylesheet" type="text/css" href="/plugins/bootstrap-fileinput/bootstrap-fileinput.css">
@endpush

@push('plugin_scripts')
<script type="text/javascript" src="/plugins/bootstrap-select/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script>
@endpush

@push('custom_scripts')
<script type="text/javascript">
    var _validFileExtensions = [".pdf"];    
    
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
                        text: 'Format file tidak diizinkan',
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

    $('#status').change(function() {
        var status = $('#status').val();
        if(status == 4) {
            $('#awb_number').slideDown();
        }
        else{
            $('#awb_number').slideUp();
        }
    })

    $(document).ready(function () {
        $(".bs-select").selectpicker();
    });
</script>
@endpush