@extends('mail.master') @section('content')
<p>Halo,
    <b>{{$customer->first_name}} {{$customer->last_name}}</b>
</p>
<div class="content">
    <p>Selamat pembayaran dengan no invoice <b>{{$order->invoice_no}}</b> berhasil.</p>
    <p>Berikut ini detail produk yang Anda pesan</p>
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

    <p>Terima kasih, happy shopping</p>
</div>
@endsection