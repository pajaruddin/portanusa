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
            <input type="text" class="form-control" maxlength="30" name="name" id="name" value="{{ (!empty($category)) ? $category->name : old('name') }}">
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
            <input type="checkbox" name="publish" class="make-switch" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" {{ (!empty($category) && $category->publish == "T") ? "checked" : "" }}>
        </div>
    </div>
    <div class="form-group">
        @php $category_parent = !empty($category->parent) ? $category->parent : old('parent'); @endphp
        <label class="col-md-3 control-label">Parent Kategori</label>
        <div class="col-md-6">
            <select name="parent" class="bs-select form-control" id="parent-category" data-live-search="true" data-size="8">
                <option value="">- Tanpa Kategori Parent -</option>
                @foreach ($parent_category as $parent)
                    <option value="{{ $parent->id }}" {{ $category_parent == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                    @if(count($child_category) > 0)
                        @foreach($child_category as $child)
                            @if($child->parent === $parent->id)
                                <option value="{{ $child->id }}" {{ $category_parent == $child->id ? 'selected' : '' }}>- {{ $child->name }}</option>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group display-home" style="height: 30px;">
        <label class="control-label col-md-3">Display Home</label>
        <div class="col-md-9">
            <input type="checkbox" name="display_home" class="make-switch home-display" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" {{ (!empty($category) && $category->display_home == "T") ? "checked" : "" }}>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Deskripsi</label>
        <div class="col-md-8">
            <textarea class="form-control" name="description_indonesia">
                {{ (!empty($category)) ? $category->description : old('description') }}
            </textarea>
        </div>
    </div>
    <div class="form-group last">
        @php $banner_src = !empty($category->banner_image) ? AppConfiguration::assetPortalDomain()->value.  "/" . AppConfiguration::categoryBannerImagePath()->value . "/" . $category->banner_image : "http://www.placehold.it/450x200/EFEFEF/AAAAAA&amp;text=no+image"; @endphp
        <label class="control-label col-md-3">Gambar Banner Kategori</label>
        <div class="col-md-5">
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 450px; height: 200px;">
                    <img src="{{ $banner_src }}" alt="" /> 
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 450px; max-height: 200px;"> </div>
                <div>
                    <span class="btn default btn-file">
                        @if (!empty($category->banner_image))
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
    <div class="form-group last">
        @php $thumb_src = !empty($category->thumb_image) ? AppConfiguration::assetPortalDomain()->value.  "/" . AppConfiguration::categoryThumbImagePath()->value . "/" . $category->thumb_image : "http://www.placehold.it/200x200/EFEFEF/AAAAAA&amp;text=no+image"; @endphp
        <label class="control-label col-md-3">Gambar Thumbnail Kategori </label>
        <div class="col-md-5">
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                    <img src="{{ $thumb_src }}" alt="" /> 
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"> </div>
                <div>
                    <span class="btn default btn-file">
                        @if (!empty($category->thumb_image))
                        <span class="fileinput-new"> Ubah Gambar </span>                            
                        @else
                        <span class="fileinput-new"> Pilih Gambar </span>                            
                        @endif
                        <span class="fileinput-exists"> Ubah Gambar</span>
                        <input type="file" name="thumb_image" onchange="validateImage(this);">
                    </span>
                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput">Batalkan</a>
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
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Meta Deskripsi</label>
        <div class="col-md-6">
            <textarea class="form-control" name="meta_description">{{ (!empty($category)) ? $category->meta_description : old('meta_description') }}</textarea>
            <div class="form-control-focus"> </div>
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Meta Keyword</label>
        <div class="col-md-6">
            <textarea class="form-control" name="meta_keyword">{{ (!empty($category)) ? $category->meta_keyword : old('meta_keyword') }}</textarea>
            <div class="form-control-focus"> </div>
        </div>
    </div>
    <div class="form-group" style="height: 30px;">
        <label class="control-label col-md-3">Highlight</label>
        <div class="col-md-9">
            <input type="checkbox" name="highlight" id="highlight" onchange="checkHighlight()" class="make-switch" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" {{ (!empty($category) && $category->highlight == "T") ? "checked" : "" }}>
        </div>
    </div>
    <div class="form-group" id="related" style="display:none;">
        <label class="col-md-3 control-label">Produk Terkait</label>
        <div class="col-md-5">
            <select name="product_related[]" id="select2-button-addons-single-input-group-sm" class="form-control select-related" multiple style="width: 100%;">
                @if(!empty($product_relateds))
                    @foreach($product_relateds as $product_related)
                        <option value="{{ $product_related->product_id }}" selected="selected">{{ $product_related->name." ".$product_related->code }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    {{-- <div class="form-group form-md-line-input {{ ($errors->has('url')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">URL
            <span class="required">*</span>
        </label>
        <div class="col-md-6">                                            
            <input type="text" class="form-control" name="url" value="{{ (!empty($category)) ? $category->url : old('url') }}" style="text-transform: lowercase;">            
            @if ($errors->has('url'))
            <span class="text-danger">{{ $errors->first('url') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div> --}}
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" class="btn green">Simpan</button>
            <a href="{{ url($menu) }}" class="btn btn-danger"><i class="fa fa-undo"></i> Kembali</a>
        </div>
    </div>
</div>