<div class="form-body">
        @if(Session::has('alert-error'))
        <div class="alert alert-danger">
            <button class="close" data-close="alert"></button>
            {{ Session::get('alert-error') }}
        </div>
        @endif
        <span class="text-danger bold">(*) Wajib Diisi</span>
    
        <div class="form-group form-md-line-input {{ ($errors->has('first_name')) ? "has-error" : "" }}">
            <label class="col-md-3 control-label">Nama
                <span class="required">*</span>
            </label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="first_name" value="{{ (!empty($user_access)) ? $user_access->first_name : old('first_name') }}" placeholder="Nama Depan">
                @if ($errors->has('first_name'))
                <span class="text-danger">{{ $errors->first('first_name') }}</span>
                @else
                <div class="form-control-focus"> </div>
                @endif
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="last_name" value="{{ (!empty($user_access)) ? $user_access->last_name : old('last_name') }}" placeholder="Nama Belakang">
                <div class="form-control-focus"> </div>
            </div>
        </div>
        <div class="form-group form-md-line-input {{ ($errors->has('email')) ? "has-error" : "" }}">
            <label class="col-md-3 control-label">Email
                <span class="required">*</span>
            </label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="email" value="{{ (!empty($user_access)) ? $user_access->email : old('email') }}">
                @if ($errors->has('email'))
                <span class="text-danger">{{ $errors->first('email') }}</span>
                @else
                <div class="form-control-focus"> </div>
                @endif
            </div>
        </div>
        <div class="form-group form-md-line-input">
            <label class="col-md-3 control-label">No. Telepon</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="telephone" maxlength="15" onkeydown="return isNumberKey(event);" value="{{ (!empty($user_access)) ? $user_access->telephone : old('telephone') }}">
                <div class="form-control-focus"> </div>
            </div>
        </div>
        <div class="form-group form-md-line-input">
            <label class="col-md-3 control-label">No. Handphone</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="handphone" maxlength="15" onkeydown="return isNumberKey(event);" value="{{ (!empty($user_access)) ? $user_access->handphone : old('handphone') }}">
                <div class="form-control-focus"> </div>
            </div>
        </div>
        <div class="form-group form-md-line-input {{ ($errors->has('role')) ? "has-error" : "" }}">
            @php $role_selected = !empty($user_access) ? $role_users->role_id : ''; @endphp
            <label class="col-md-3 control-label">Grup Akses
                <span class="required">*</span>
            </label>
            <div class="col-md-6">
                <select name="role" class="form-control" id="module">
                    <option value="">- Pilih Grup Akses -</option>
                    @if(!empty($roles))
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ ($role_selected == $role->id) ? "selected" : "" }}>{{ $role->display_name }}</option>
                    @endforeach
                    @endif
                </select>
                @if ($errors->has('role'))
                <span class="text-danger">{{ $errors->first('role') }}</span>
                @endif
            </div>
        </div>
    </div>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <button type="submit" class="btn green">Simpan</button>
                <a href="{{ url($menu) }}" class="btn btn-danger"><i class="fa fa-undo"></i> Kembali</a>
            </div>
        </div>
    </div>