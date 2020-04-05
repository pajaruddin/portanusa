<div class="form-body">
        @if(Session::has('alert-error'))
        <div class="alert alert-danger">
            <button class="close" data-close="alert"></button>
            {{ Session::get('alert-error') }}
        </div>
        @endif
        <span class="text-danger bold">(*) Wajib Diisi</span>
    
        <div class="form-group form-md-line-input {{ ($errors->has('name')) ? "has-error" : "" }}">
            <label class="col-md-3 control-label">Nama Kategori
                <span class="required">*</span>
            </label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="name" id="name" value="{{ (!empty($category)) ? $category->name : old('name') }}">
                @if ($errors->has('name'))
                <span class="text-danger">{{ $errors->first('name') }}</span>
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