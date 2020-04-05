@extends('mail.master')
@section('content')
<p>Halo, <b>{{$name}}</b></p>
<div class="content">
<p>
Anda menerima email ini karena anda mengajukan permintaan reset password. Silahkan klik link di bawah ini untuk mengatur ulang password anda.
</p>
<br/>
<p style="text-align:center">
<a href="{{ $primary_domain }}/reset-password/{{$forgotten_password_code}}" style="background-color:#0e8ce4;width:200px;text-align:center;padding:10px 0px;color:#ffffff;font-size:14px;display:inline-block">Reset Password</a><br/>
</p>
</div>
@endsection