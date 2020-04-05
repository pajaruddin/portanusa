<form method="POST" action="{{ url("/profile/personal") }}" autocomplete="off" role="form">
    {{ csrf_field() }}
    <div class="form-group">
        <label class="control-label">Nama Depan
            <span class="required">*</span>
        </label>
        <input type="text" name="first_name" class="form-control" value="{{ Auth::user()->first_name }}"/>
        @if ($errors->has('first_name'))
        <span class="text-danger">{{ $errors->first('first_name') }}</span>
        @endif
    </div>
    <div class="form-group">
        <label class="control-label">Nama Belakang</label>
        <input type="text" name="last_name" class="form-control" value="{{ Auth::user()->last_name }}"/>
    </div>
    <div class="form-group">
        <label class="control-label">Email</label>
        <input type="text" name="email" class="form-control" value="{{ Auth::user()->email }}" disabled=""/>
    </div>
    <div class="form-group">
        <label class="control-label">No. Telepon</label>
        <input type="text" name="telephone" maxlength="15" onkeydown="return isNumberKey(event);" class="form-control" value="{{ Auth::user()->telephone }}"/>
    </div>
    <div class="form-group">
        <label class="control-label">No. Handphone</label>
        <input type="text" name="handphone" class="form-control" onkeydown="return isNumberKey(event);" value="{{ Auth::user()->handphone }}"/>
    </div>
    <div class="margiv-top-10">
        <button type="submit" class="btn green">Simpan</button>
    </div>
</form>