@extends('mail.master')
@section('content')
<p>Halo, <b>{{$name}}</b></p>
<div class="content">
    <p>Terima kasih telah berbelanja di <a href="{{ $primary_domain }}">{{ $primary_domain }}</a></p>
    <p>
    Mohon segera selesaikan pembayaran anda <b>{{ $order->invoice_no }}</b>
    </p>
    <br/>
    <?php $total_price = 0 ?>
    @if(count($order_products) != 0)
    <table border="1" style="width:100%" cellspacing="0" cellpadding="10">
        <tr>
            <td style="width:250px">Deskripsi</td>
            <td style="text-align: right">Total</td>
        </tr>
        @foreach($order_products as $product)
        <tr>
            <td>
                {{ $product->name }}
                <br/>
                Rp {{ number_format($product->price, 0, ',', '.') }} * {{ $product->quantity }}
            </td>
            <?php 
            $total_price_product = $product->price * $product->quantity; 
            $total_price += $total_price_product;
            ?>
            <td style="text-align: right">Rp {{ number_format($total_price_product, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr>
            <th style="text-align: right">Subtotal</th>
            <th style="text-align: right">Rp {{ number_format($total_price, 0, ',', '.') }}</th>
        </tr>
        @if(!empty($order->voucher_code))
        <?php $discount_price = $order->discount_price; ?>
        <tr>
            <th style="text-align: right">Diskon</th>
            <th style="text-align: right">Rp {{ number_format($order->discount_price, 0, ',', '.') }}</th>
        </tr>
        <tr>
            <th style="text-align: right">Ongkos Kirim</th>
            <th style="text-align: right">Rp {{ number_format($order->shipping_price, 0, ',', '.') }}</th>
        </tr>
        <tr>
            <th style="text-align: right">Total</th>
            <th style="text-align: right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</th>
        </tr>
        @endif
    </table>
    @endif
    <hr/>
    <p>
        Pembayaran akan kami batalkan jika kami belum menerima laporan pembayaran selama 48 jam.
    </p>
    <p>Cara Membayar di ATM</p>
    <ul style="font-size:12px">
        <li>Masukkan PIN</li>
        <li>Pilih "TRANSFER". Apabila menggunakan ATM BCA, pilih "Transaksi lainnya" lalu "Transfer"</li>
        <li>Pilih "KE REK BANK LAIN"</li>
        <li>Masukkan Kode Bank kemudian tekan "Benar"</li>
        <li>Masukkan Jumlah pembayaran sesuai dengan yang ditagihkan (Jumlah yang ditransfer harus sama persis tidak boleh lebih dan kurang) Jumlah nominal yang tidak sesuai dengan tagihan akan menyebabkan transaksi gagal</li>
        <li>Isi Nomor Rekening tujuan</li>
        <li>Muncul Layar Konfirmasi Transfer yang berisi nomor rekening tujuan dan Nama beserta jumlah yang dibayar, jika sudah benar, Tekan "Benar"</li>
        <li>Selesai.</li>
    </ul>
</div>
@endsection