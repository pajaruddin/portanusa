<div class="form-body">
    @if(Session::has('alert-error'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        {{ Session::get('alert-error') }}
    </div>
    @endif
    <span class="text-danger bold">(*) Wajib Diisi</span>

    <div class="form-group form-md-line-input {{ ($errors->has('name')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Nama
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="name" value="{{ (!empty($customer_role)) ? $customer_role->name : old('name') }}" placeholder="Nama">
            @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>

    <div class="form-group form-md-line-input {{ ($errors->has('display_name')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Tampilan Nama
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="display_name" value="{{ (!empty($customer_role)) ? $customer_role->display_name : old('display_name') }}" placeholder="Tampilan Nama">
            @if ($errors->has('display_name'))
            <span class="text-danger">{{ $errors->first('display_name') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>

    <div class="form-group form-md-line-input {{ ($errors->has('description')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Deskripsi
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="description" value="{{ (!empty($customer_role)) ? $customer_role->description : old('description') }}" placeholder="Deskripsi">
            @if ($errors->has('description'))
            <span class="text-danger">{{ $errors->first('description') }}</span>
            @else
            <div class="form-control-focus"> </div>
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