<div class="form-body">
    @if(Session::has('alert-error'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        {{ Session::get('alert-error') }}
    </div>
    @endif
    <span class="text-danger bold">(*) Wajib Diisi</span>

    <div class="form-group form-md-line-input {{ ($errors->has('name')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Nama Bank
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="name" id="name" value="{{ (!empty($bank)) ? $bank->name : old('name') }}">
            @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group form-md-line-input {{ ($errors->has('no_rek')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">No Rekening
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="no_rek" id="no_rek" value="{{ (!empty($bank)) ? $bank->no_rek : old('no_rek') }}" onkeypress="return decimalValue(event);">
            @if ($errors->has('no_rek'))
            <span class="text-danger">{{ $errors->first('no_rek') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group form-md-line-input {{ ($errors->has('name_of')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Atas Nama
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="name_of" id="name_of" value="{{ (!empty($bank)) ? $bank->name_of : old('name_of') }}">
            @if ($errors->has('name_of'))
            <span class="text-danger">{{ $errors->first('name_of') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group last">
        @php $logo_src = !empty($bank->logo) ? AppConfiguration::assetPortalDomain()->value.  "/" . AppConfiguration::bankLogoPath()->value . "/" . $bank->logo : "http://www.placehold.it/450x200/EFEFEF/AAAAAA&amp;text=no+image"; @endphp
        <label class="control-label col-md-3">Logo Bank <span class="required">*</span></label>
        <div class="col-md-5">
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 450px; height: 200px;">
                    <img src="{{ $logo_src }}" alt="" /> 
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 450px; max-height: 200px;"> </div>
                <div>
                    <span class="btn default btn-file">
                        @if (!empty($bank->logo))
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
                <span class="label label-danger">NOTE!</span> Format logo harus JPG atau PNG.
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
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" class="btn green">Simpan</button>
            <a href="{{ url($menu) }}" class="btn btn-danger"><i class="fa fa-undo"></i> Kembali</a>
        </div>
    </div>
</div>