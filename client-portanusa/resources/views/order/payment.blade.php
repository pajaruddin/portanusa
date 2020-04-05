@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
<div class="cart mt-5 mb-5">
    <div class="container">
        <div class="wizard">
            <div class="wizard-inner">
                <div class="connecting-line"></div>
                <ul class="nav nav-tabs d-flex justify-content-center" role="tablist">
                    <li role="presentation" class="nav-item">
                        <a href="javascript:;" data-toggle="tab" role="tab" title="Shipping Address" class="nav-link disabled">
                            <span class="round-tab">
                                <i class="fa fa-truck"></i>
                            </span>
                        </a>
                    </li>
                    <li role="presentation" class="nav-item">
                        <a href="javascript:;" data-toggle="tab" role="tab" title="Payment" class="nav-link active">
                            <span class="round-tab">
                                <i class="fas fa-money-check"></i>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show text-center mb-4" role="alert">
                {!! session('success') !!}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="alert alert-info alert-dismissible fade show text-center mb-4" role="alert">
            You can go to your history order in account to complete your payment later
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="content-cart">
                    <h4 class="text-header mb-4 mt-3">
                        Product List
                    </h4>
                    @foreach($order_products as $product)
                    <div class="content-cart mb-3 product-list">
                        <div class="row">
                            <div class="col-md-8 align-self-center">
                                <h4>{{ $product->name }}</h4>
                                <h4 class="price-content">Rp {{ number_format($product->price, 0, 0, '.') }}</h4>
                                <div class="quantity-content">
                                    Total : {{ $product->quantity }}<br/>
                                    @if($product->product_status == "Pre Order")
                                    Status :
                                    {{ $product->product_status }}
                                    <?php 
                                    $date_end = date('Y-m-d', strtotime($order->created_at . ' + ' . $product->pre_order_time . ' days'));
                                    ?>
                                    ({{ date('j F Y', strtotime($date_end)) }})
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 align-self-center text-right">
                                <?php 
                                $total_price_product = $product->price * $product->quantity;
                                ?>
                                <h4>Rp {{ number_format($total_price_product, 0, 0, '.') }}</h4>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if(count($banks) != 0)
                <div class="content-cart mt-4">
                    <h4 class="text-header mb-4 mt-3">Bank List</h4>
                    <div class="list-bank"> 
                        <div class="row">
                            @foreach($banks as $bank)
                            <div class="col-md-6">
                                <div class="card w-100 mb-3">
                                    <div class="card-image p-2 text-center">
                                        <img src="{{ $asset_domain.'/'.$bankImagePath.'/'.$bank->logo }}" />
                                    </div>
                                    <div class="card-body">
                                        <h4 class="card-title">{{ $bank->name }}</h4>
                                        <p class="card-text">
                                            Account Number : {{ $bank->no_rek }}<br/>
                                            Account Name : {{ $bank->name_of }}
                                        </p>
                                    </div>
                                </div>
                            </div>    
                            @endforeach
                        </div>   
                    </div>
                </div>
                @endif
            </div>
            <div class="col-md-4">
                <div class="content-cart summary">
                    <h4 class="text-header mb-4 mt-3">Shopping Summary</h4>
                    <a href="javascript:;" class="btn btn-primary btn-sm upload-button w-100 pt-2 pb-2 mb-4" data-invoiceNo="{{ $order->invoice_no }}" data-id="{{ $order->id }}">Pay Now</a>
                    <h5>
                        Total price ({{ $total_product }} Product)
                        <b class="float-right">Rp {{ number_format($total_price, 0, 0, '.') }}</b>
                        <input type="hidden" name="total_price" value="{{ $total_price }}" />
                    </h5>
                    @if(!empty($order->voucher_code))
                    <h5>
                        Discount
                        <b class="float-right" style="color:red">- Rp {{ number_format($order->discount_price, 0, 0, '.') }}</b>
                    </h5>
                    @endif
                    <h5>
                        Shipping Cost
                        <b class="float-right shipping-cost-content">Rp {{ number_format($order->shipping_price, 0, 0, '.') }}</b>
                    </h5>
                    <h5>
                        Grand price
                        <b class="float-right grand-price-content">Rp {{ number_format($order->total_price, 0, 0, '.') }}</b>
                    </h5>
                    <hr/>
                    <h5>
                        Shipping Courier
                        <br/>
                        <b>
                            JNE 
                            @if($order->shipping_type == "CTC")
                            REG
                            @elseif($order->shipping_type == "CTCYES")
                            YES
                            @else
                            {{ $order->shipping_type }}
                            @endif
                        </b>
                    </h5>
                    <h5>
                        Shipping Address
                        <br/>
                        <b>
                            {{$order->shipping_address.", ".$order->shipping_city.", ".$order->shipping_postal_code}}
                            <br/>
                            {{$order->shipping_province}}
                        </b>
                    </h5>
                    <h5>
                        Receiver
                        <br/>
                        <b>
                            {{$order->shipping_name."-".$order->shipping_phone}}
                        </b>
                    </h5>
                </div>
            </div>
        </div>
        
    </div>
</div>

@include('user.historyOrderModal')
@endsection

@push('custom_styles')
<link rel="stylesheet" href="/css/global.css">
<link rel="stylesheet" href="/css/cart.css">
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