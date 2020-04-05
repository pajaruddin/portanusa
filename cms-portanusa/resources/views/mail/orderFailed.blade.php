@extends('mail.master') @section('content')
<p>Halo,
    <b>{{$customer->first_name}} {{$customer->last_name}}</b>
</p>
<div class="content">
    <p>Mohon maaf transaksi Anda dengan no invoice <b>{{$order->invoice_no}}</b> telah dibatalkan.</p>
    <br>
    <p>Terima kasih, happy shopping</p>
</div>
@endsection