<div class="form-body">
    @if(Session::has('alert-error'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        {{ Session::get('alert-error') }}
    </div>
    @endif
    <span class="text-danger bold">(*) Wajib Diisi</span>

    <div class="form-group form-md-line-input {{ ($errors->has('title')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Title Service
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="title" id="title" value="{{ (!empty($service)) ? $service->title : old('title') }}">
            @if ($errors->has('title'))
            <span class="text-danger">{{ $errors->first('title') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group form-md-line-input {{ ($errors->has('description_service')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Description
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="description_service" id="description_service" value="{{ (!empty($service)) ? $service->description : old('description') }}">
            @if ($errors->has('description_service'))
            <span class="text-danger">{{ $errors->first('description_service') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group last">
        @php $logo_src = !empty($service->image) ? AppConfiguration::assetPortalDomain()->value.  "/" . AppConfiguration::servicePath()->value . "/" . $service->image : "http://www.placehold.it/200x200/EFEFEF/AAAAAA&amp;text=no+image"; @endphp
        <label class="control-label col-md-3">Logo Service <span class="required">*</span></label>
        <div class="col-md-5">
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                    <img src="{{ $logo_src }}" alt="" /> 
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"> </div>
                <div>
                    <span class="btn default btn-file">
                        @if (!empty($service->image))
                        <span class="fileinput-new"> Ubah Logo </span>                            
                        @else
                        <span class="fileinput-new"> Pilih Logo </span>                            
                        @endif
                        <span class="fileinput-exists"> Ubah Logo</span>
                        <input type="file" name="logo" value="{{$service->image}}" onchange="validateImage(this);">
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