<div class="alert alert-info">
    <ul>
        <li>Panjang minimal karakter password adalah 8 karakter</li>
        <li>Password harus mengandung huruf kapital, huruf kecil, karakter spesial (!, $, #, or %) dan angka</li>
        <li>Password hanya diperbolehkan diubah maksimal satu kali dalam sehari.</li>
        <li>Contoh password : #Myp4ssw0rd!</li>
    </ul>
</div>

<form method="POST" action="{{ url("/profile/password") }}" autocomplete="off" role="form">
    {{ csrf_field() }}
    <div class="form-group">
        <label class="control-label">Password Lama
            <span class="required">*</span>
        </label>
        <input type="password" name="old_password" class="form-control"/>
        @if ($errors->has('old_password'))
        <span class="text-danger">{{ $errors->first('old_password') }}</span>
        @endif
    </div>
    <div class="form-group">
        <label class="control-label">Password Baru
            <span class="required">*</span>
        </label>
        <input type="password" name="password" class="form-control"/>
        @if ($errors->has('password'))
        <span class="text-danger">{{ $errors->first('password') }}</span>
        @endif
    </div>
    <div class="form-group">
        <label class="control-label">Konfirmasi Password
            <span class="required">*</span>
        </label>
        <input type="password" name="password_confirmation" class="form-control"/>
        @if ($errors->has('password_confirmation'))
        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
        @endif
    </div>
    <div class="margiv-top-10">
        <button type="submit" class="btn green">Simpan</button>
    </div>
</form>