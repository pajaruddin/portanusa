@extends('mail.master') @section('content')
<p>Halo,
    <b>{{$customer->first_name}} {{$customer->last_name}}</b>
</p>
<div class="content">
    <p>Selamat pesanan Anda dengan no invoice <b>{{$order->invoice_no}}</b> dan nomor resi <b>{{$order->awb_number}}</b> sedang dalam pengiriman.</p>
    <br>
    <p>Terima kasih, happy shopping</p>
</div>
@endsection