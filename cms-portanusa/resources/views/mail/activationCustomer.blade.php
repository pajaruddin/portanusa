@extends('mail.master')
@section('content')
<p>Halo, <b>{{$name}}</b></p>
<div class="content">
<p>
    Selamat email {{$email}} berhasil teraktivasi. 
</p>
</p>
</div>
@endsection