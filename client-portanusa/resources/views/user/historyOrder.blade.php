@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
    <div class="container">
        <div class="account mt-5 mb-5">
            <div class="row">
                <div class="col-sm-3">
                    @include('user.navigation')
                </div>
                <div class="col-sm-9">
                    <div class="history-order">
                        <h3 class="font-weight-light">History Order</h3>
                        @if(count($orders_status) != 0)
                        <ul class="nav nav-tabs" id="historyOrderTab" role="tablist">
                            <?php $no = 1; ?>
                            @foreach($orders_status as $status)
                            <?php $status_label = DisplayStatusOrder::getStatus($status->id) ?>
                            <li class="nav-item">
                                <a class="nav-link {{ $no == 1 ? 'active' : '' }}" id="{{ $status->id }}content-tab" data-toggle="tab" href="#{{ $status->id }}content" role="tab" aria-controls="{{ $status->id }}content" aria-selected="true">{{ $status_label['highlight'] }}</a>
                            </li>
                            <?php $no++; ?>
                            @endforeach
                        </ul>
                        <div class="tab-content mt-3" id="historyOrderTabContent">
                            <?php $no = 1; ?>
                            @foreach($orders_status as $status)
                            <div class="tab-pane fade {{ $no == 1 ? 'show active' : '' }}" id="{{ $status->id }}content" role="tabpanel" aria-labelledby="{{ $status->id }}content-tab">
                                @if(count($orders[$status->id]) != 0)
                                <div class="accordion" id="accordionHistoryOrder{{ $status->id }}">
                                    @foreach($orders[$status->id] as $order)
                                    <?php $status_label = DisplayStatusOrder::getStatus($order->status) ?>
                                    <div class="card mb-3">
                                        <div class="card-header" id="heading{{ $order->id }}" data-toggle="collapse" data-target="#collapse{{ $order->id }}" aria-expanded="true" aria-controls="collapse{{ $order->id }}">
                                            <h5 class="mb-0">
                                            {{ $order->invoice_no }}
                                            </h5>
                                            <h6>
                                                Date order : <b>{{ date('j F Y', strtotime($order->created_at)) }}</b>
                                            </h6>
                                            <div class="alert alert-{{ $status_label['label'] }} mb-2" role="alert">
                                            {{ $status_label['message'] }}
                                            </div>
                                            @if(empty($order->transfer_image))
                                            <a href="javascript:;" class="btn btn-primary btn-sm upload-button" data-invoiceNo="{{ $order->invoice_no }}" data-id="{{ $order->id }}">Upload Transfer Image</a>
                                            @endif
                                        </div>
                                    
                                        <div id="collapse{{ $order->id }}" class="collapse" aria-labelledby="heading{{ $order->id }}" data-parent="#accordionHistoryOrder{{ $status->id }}">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td style="width:60%">
                                                                <b>Shipping Address</b><br/>
                                                                <b>{{ $order->shipping_name }}</b><br/>
                                                                {{ $order->shipping_address }}<br/>
                                                                {{ $order->shipping_province.", ".$order->shipping_city.", ".$order->shipping_postal_code }}<br/>
                                                                Phone : {{ $order->shipping_phone }}
                                                                @if(!empty($order->awb_number))
                                                                <br/><br/>
                                                                <b>Shipping Number</b><br/>
                                                                {{ $order->awb_number }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <b>Total Product</b><br/>
                                                                {{ $total_product[$order->id]->total_qty }} product<br/><br/>
                                                                @if(!empty($order->voucher_code))
                                                                <b>Discount</b><br/>
                                                                Rp {{ number_format($order->discount_price, 0, 0, '.') }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <b>Shipping Price</b><br/>
                                                                Rp {{ number_format($order->shipping_price, 0, 0, '.') }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">
                                                                <b><i class="fa fa-list"></i> List Produk : </b>
                                                            </td>
                                                        </tr>
                                                        @foreach($order_products[$order->id] as $product)
                                                        <tr>
                                                            <td>
                                                                <b>{{ $product->name }}</b><br/>
                                                            </td>
                                                            <td>
                                                                <b>Quantity</b><br/>
                                                                {{ $product->quantity }} product x Rp {{ number_format($product->price, 0, 0, '.') }}
                                                                @if($product->product_status == "Pre Order")
                                                                <br/>
                                                                <b>Status</b><br/>
                                                                {{ $product->product_status }}
                                                                <?php 
                                                                $date_end = date('Y-m-d', strtotime($order->created_at . ' + ' . $product->pre_order_time . ' days'));
                                                                ?>
                                                                <br/>
                                                                ({{ date('j F Y', strtotime($date_end)) }})
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <b>Total Price</b><br/>
                                                                <?php 
                                                                    $product_price = $product->quantity * $product->price;
                                                                    ?>
                                                                Rp {{ number_format($product_price, 0, 0, '.') }}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td colspan="3" class="text-right">
                                                                <b>Grand Price : Rp {{ number_format($order->total_price, 0, 0, '.') }}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="text-left">
                                                                <a href="/account/history-order/download/{{ $order->id }}" class="btn btn-info btn-sm"><i class="far fa-file-pdf"></i> Download PDF</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="alert alert-secondary" role="alert">
                                There is no data in this status
                                </div>
                                @endif
                            </div>
                            <?php $no++; ?>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('user.historyOrderModal')
@endsection

@push('custom_styles')
<link rel="stylesheet" href="/css/global.css">
<link rel="stylesheet" href="/css/user.css">
@endpush

@push('custom_scripts')
<script src="/js/global.js"></script>
<script>
$(function(){
    initTransferModal();
});

function initTransferModal() {
    var items = document.getElementsByClassName('upload-button');
    for (var x = 0; x < items.length; x++) {
        var item = items[x];
        item.addEventListener('click', function(fn) {
            var invoice_no  = $(this).attr("data-invoiceNo");
            var id  = $(this).attr("data-id");
            $('#transferModalLabel').html(invoice_no);
            $('#text-invoice-no').val(invoice_no);
            $('#text-order-id').val(id);
            $('#transferModal').modal('show');
        });
    }
}
</script>
@endpush