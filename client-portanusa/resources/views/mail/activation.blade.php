@extends('mail.master')
@section('content')
<p>Halo, <b>{{$name}}</b></p>
<div class="content">
    <p>Kamu telah mendaftarkan email {{ $email }} sebagai alamat email kamu di ecommerce Portanusa.</p>
    <p>
    Ayo veriﬁkasi email kamu untuk dapat login di halaman ecommerce Portanusa
    </p>
    <br/>
    <p style="text-align:center">
    <a href="{{$primary_domain}}/activation/{{$activation}}" style="background-color:#0e8ce4;width:200px;text-align:center;padding:10px 0px;color:#ffffff;font-size:14px;display:inline-block">Verifikasi Email</a><br/>
    </p>
</div>
@endsection