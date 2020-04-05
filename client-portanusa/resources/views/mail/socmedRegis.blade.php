@extends('mail.master')
@section('content')
<p>Halo, <b>{{$name}}</b></p>
<div class="content">
    <p>Kamu telah berhasil terdaftar dengan email {{ $email }} di ecommerce Portanusa.</p>
</div>
@endsection