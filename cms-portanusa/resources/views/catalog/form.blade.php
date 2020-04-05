<div class="form-body">
    @if(Session::has('alert-error'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        {{ Session::get('alert-error') }}
    </div>
    @endif
    <span class="text-danger bold">(*) Wajib Diisi</span>

    <div class="form-group form-md-line-input {{ ($errors->has('title')) ? 'has-error' : '' }}">
        <label class="col-md-3 control-label">Nama Katalog
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="title" value="{{ (!empty($catalogue)) ? $catalogue->title : old('title') }}">
            @if ($errors->has('title'))
            <span class="text-danger">{{ $errors->first('title') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif 
        </div>
    </div>
    </div>
    <div class="form-group form-md-line-input {{ ($errors->has('document_url')) ? 'has-error' : '' }}">
        <label class="col-md-3 control-label">Dokumen URL
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="document_url" value="{{ (!empty($catalogue)) ? $catalogue->document_url : old('document_url') }}">
            @if ($errors->has('document_url'))
            <span class="text-danger">{{ $errors->first('document_url') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif 
        </div>
    </div>
    <div class="form-group last">
        @php $thumb_src = !empty($catalogue->thumb_image) ? AppConfiguration::assetPortalDomain()->value.  "/" . AppConfiguration::catalogsThumbPath()->value . "/" . $catalogue->thumb_image : "http://www.placehold.it/300x200/EFEFEF/AAAAAA&amp;text=no+image"; @endphp
        <label class="control-label col-md-3">Gambar Thumbnail
        	@if(empty($catalogue->thumb_image))
            <span class="required">*</span>
            @else
            @endif
        </label>
        <div class="col-md-6">
            <div class="fileinput fileinput-new" data-provides="fileinput">
        
                <div class="fileinput-new thumbnail" style="width: 300px; height: 200px;">
                    <img src="{{ $thumb_src }}" alt="" /> 
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 300px; max-height: 200px;"> </div>
                <div>
                    <span class="btn default btn-file">
                        @if (!empty($catalogue->thumb_image))
                        <span class="fileinput-new"> Ubah Gambar </span>                            
                        @else
                        <span class="fileinput-new"> Pilih Gambar </span>                            
                        @endif
                        <span class="fileinput-exists"> Ubah Gambar</span>
                        <input type="file" name="thumb_image" onchange="validateImage(this);">
                    </span>
                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                </div>
            </div>
            <div class="clearfix margin-top-10">
                <span class="label label-danger">NOTE!</span> Format gambar harus JPG atau PNG.
            </div>
            @if ($errors->has('thumb_image'))
            <div class="clearfix margin-top-10">
                <span class="text-danger">
                    {{ $errors->first('thumb_image') }}
                </span>
            </div>
            @endif
        </div>
    </div>
    <div class="form-group" style="height: 30px;">
        <label class="control-label col-md-3">Publish</label>
        <div class="col-md-9">
            <input type="checkbox" name="publish" class="make-switch" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" {{ (!empty($catalogue) && $catalogue->publish == "T") ? "checked" : "" }}>
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