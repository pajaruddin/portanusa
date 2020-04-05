<div class="form-body">
    <span class="text-danger bold">(*) Wajib Diisi</span>
    <div class="form-group form-md-line-input {{ ($errors->has('email')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Email
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="email" class="form-control" name="email" value="{{ (!empty($setting)) ? $setting->email : old('email') }}">
            @if ($errors->has('email'))
            <span class="text-danger">{{ $errors->first('email') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Telepon
            <span class="required">*</span>
        </label>
        <div class="col-md-5">
            <input type="text" name="phone" class="form-control" onkeydown="return isNumberKey(event);" value="{{ (!empty($setting)) ? $setting->phone : old('phone') }}"/>
            @if ($errors->has('phone'))
            <span class="text-danger">{{ $errors->first('phone') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group {{ ($errors->has('address')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Alamat
            <span class="required"> </span>
        </label>
        <div class="col-md-8">
            <textarea class="text-area" id="address" name="address">{{ (!empty($setting)) ? $setting->address : old('address') }}</textarea>
            @if ($errors->has('address'))
            <span class="text-danger">{{ $errors->first('address') }}</span>
            @endif
        </div>
    </div>
    </div>
    <div class="form-group last">
        @php $logo_image_src = !empty($setting->logo) ? AppConfiguration::assetPortalDomain()->value.  "/" . AppConfiguration::logoPath()->value . "/" . $setting->logo : "http://www.placehold.it/450x200/EFEFEF/AAAAAA&amp;text=no+image"; @endphp
        <label class="control-label col-md-3">Gambar Logo <span class="required">*</span></label>
        <div class="col-md-5">
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 450px; height: 200px;">
                    <img src="{{ $logo_image_src }}" alt="" /> 
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 450px; max-height: 200px;"> </div>
                <div>
                    <span class="btn default btn-file">
                        @if (!empty($setting->image))
                        <span class="fileinput-new"> Ubah Logo </span>                            
                        @else
                        <span class="fileinput-new"> Pilih Logo </span>                            
                        @endif
                        <span class="fileinput-exists"> Ubah Logo</span>
                        <input type="file" name="logo" onchange="validateImage(this);">
                    </span>
                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput">Batalkan</a>
                </div>
            </div>
            <div class="clearfix margin-top-10">
                <span class="label label-danger">NOTE!</span> Format gambar harus JPG atau PNG.
            </div>
            @if ($errors->has('logo'))
            <div class="clearfix margin-top-10">
                <span class="text-danger">
                    {{ $errors->first('logo') }}
                </span>
            </div>
            @endif
        </div>
    </div>
    <div class="form-group last">
        @php $icon_image_src = !empty($setting->icon) ? AppConfiguration::assetPortalDomain()->value.  "/" . AppConfiguration::logoPath()->value . "/" . $setting->icon : "http://www.placehold.it/150x150/EFEFEF/AAAAAA&amp;text=no+image"; @endphp
        <label class="control-label col-md-3">Gambar Icon <span class="required">*</span></label>
        <div class="col-md-5">
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 150px; height: 150px;">
                    <img src="{{ $icon_image_src }}" alt="" /> 
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 150px; max-height: 150px;"> </div>
                <div>
                    <span class="btn default btn-file">
                        @if (!empty($setting->icon))
                        <span class="fileinput-new"> Ubah Icon </span>                            
                        @else
                        <span class="fileinput-new"> Pilih Icon </span>                            
                        @endif
                        <span class="fileinput-exists"> Ubah Icon</span>
                        <input type="file" name="icon" onchange="validateImage(this);">
                    </span>
                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput">Batalkan</a>
                </div>
            </div>
            <div class="clearfix margin-top-10">
                <span class="label label-danger">NOTE!</span> Format gambar harus JPG, PNG atau ICON.
            </div>
            @if ($errors->has('icon'))
            <div class="clearfix margin-top-10">
                <span class="text-danger">
                    {{ $errors->first('icon') }}
                </span>
            </div>
            @endif
        </div>
    </div>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" class="btn green">Simpan</button>
        </div>
    </div>
</div>