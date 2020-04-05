<div class="form-body">
    @if(Session::has('alert-error'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        {{ Session::get('alert-error') }}
    </div>
    @endif
    <span class="text-danger bold">(*) Wajib Diisi</span>

    <div class="form-group form-md-line-input {{ ($errors->has('name')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Nama Brand
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="text" class="form-control" maxlength="30" name="name" id="name" value="{{ (!empty($brand)) ? $brand->name : old('name') }}">
            @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group" style="height: 30px;">
        <label class="control-label col-md-3">Publish</label>
        <div class="col-md-9">
            <input type="checkbox" name="publish" class="make-switch" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" {{ (!empty($brand) && $brand->publish == "T") ? "checked" : "" }}>
        </div>
    </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Deskripsi</label>
        <div class="col-md-8">
            <textarea class="form-control" name="description_indonesia">
                {{ (!empty($brand)) ? $brand->description : old('description') }}
            </textarea>
        </div>
    </div>
    <div class="form-group last">
        @php $banner_image_src = !empty($brand->banner_image) ? AppConfiguration::assetPortalDomain()->value.  "/" . AppConfiguration::brandBannerImagePath()->value . "/" . $brand->banner_image : "http://www.placehold.it/450x200/EFEFEF/AAAAAA&amp;text=no+image"; @endphp
        <label class="control-label col-md-3">Gambar Banner Brand <span class="required">*</span></label>
        <div class="col-md-5">
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 450px; height: 200px;">
                    <img src="{{ $banner_image_src }}" alt="" /> 
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 450px; max-height: 200px;"> </div>
                <div>
                    <span class="btn default btn-file">
                        @if (!empty($brand->banner_image))
                        <span class="fileinput-new"> Ubah Gambar </span>                            
                        @else
                        <span class="fileinput-new"> Pilih Gambar </span>                            
                        @endif
                        <span class="fileinput-exists"> Ubah Gambar</span>
                        <input type="file" name="banner_image" onchange="validateImage(this);">
                    </span>
                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput">Batalkan</a>
                </div>
            </div>
            <div class="clearfix margin-top-10">
                <span class="label label-danger">NOTE!</span> Format gambar harus JPG atau PNG.
            </div>
            @if ($errors->has('banner_image'))
            <div class="clearfix margin-top-10">
                <span class="text-danger">
                    {{ $errors->first('banner_image') }}
                </span>
            </div>
            @endif
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Meta Deskripsi</label>
        <div class="col-md-6">
            <textarea class="form-control" name="meta_description">{{ (!empty($brand)) ? $brand->meta_description : old('meta_description') }}</textarea>
            <div class="form-control-focus"> </div>
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Meta Keyword</label>
        <div class="col-md-6">
            <textarea class="form-control" name="meta_keyword">{{ (!empty($brand)) ? $brand->meta_keyword : old('meta_keyword') }}</textarea>
            <div class="form-control-focus"> </div>
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