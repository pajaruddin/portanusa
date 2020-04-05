@extends('mail.master')
@section('content')
<p>Halo {{$full_name}}, </b></p>
<div class="content">
    <p>Terima kasih telah mengirimkan permintaan untuk posisi {{ $position }} kepada kami. Permintaan anda akan segera kami proses. Mohon menunggu konfirmasi dari kami.</p>
</div>
@endsection