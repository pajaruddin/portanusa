@extends('mail.master') @section('content')
<p>Halo,
    <b>{{$first_name}}</b>
</p>
<div class="content">
        <p>{{$created_name}} membuatkan anda akun baru. Silahkan <a href="{{$portal_login_domain}}" style="color:#1a5c00;text-decoration:underline" target="_blank">log in</a> dengan kredensial berikut ini:</p>
        <table>
            <tr>
                <td style="width: 150px">Halaman Login</td>
                <td>:</td>
                <td><a href="{{$portal_login_domain}}" target="_blank">{{$portal_login_domain}}</a></td>
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td>{{$email}}</td>
            </tr>
            <tr>
                <td>Password</td>
                <td>:</td>
                <td>{{$password}}</td>
            </tr>
        </table>
</p>
</div>
@endsection