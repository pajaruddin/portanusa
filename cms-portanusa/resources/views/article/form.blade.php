<div class="form-body">
        @if(Session::has('alert-error'))
        <div class="alert alert-danger">
            <button class="close" data-close="alert"></button>
            {{ Session::get('alert-error') }}
        </div>
        @endif
        <span class="text-danger bold">(*) Wajib Diisi</span>
        <div class="form-group form-md-line-input {{ ($errors->has('title')) ? "has-error" : "" }}">
            <label class="col-md-3 control-label">Judul Artikel
                <span class="required">*</span>
            </label>
            <div class="col-md-6">
                <input type="text" class="form-control" maxlength="30" name="title" id="title" value="{{ (!empty($article)) ? $article->title : old('title') }}">
                @if ($errors->has('title'))
                <span class="text-danger">{{ $errors->first('title') }}</span>
                @else
                <div class="form-control-focus"> </div>
                @endif
            </div>
        </div>
        <div class="form-group">
            @php $category_id = !empty($article->category_id) ? $article->category_id : old('category_id'); @endphp
            <label class="col-md-3 control-label">Kategori
                <span class="required">*</span>
            </label>
            <div class="col-md-6">
                <select name="category" class="bs-select form-control" data-live-search="true" data-size="8">
                    <option value="">- Kategori Artikel -</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group" style="height: 30px;">
            <label class="control-label col-md-3">Publish</label>
            <div class="col-md-9">
                <input type="checkbox" name="publish" class="make-switch" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" {{ (!empty($article) && $article->publish == "T") ? "checked" : "" }}>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Deskripsi</label>
            <div class="col-md-8">
                <textarea class="form-control" name="description_indonesia">
                    {{ (!empty($article)) ? $article->description : old('description') }}
                </textarea>
            </div>
        </div>
        <div class="form-group last">
            @php $banner_image_src = !empty($article->banner_image) ? AppConfiguration::assetPortalDomain()->value.  "/" . AppConfiguration::articleBannerPath()->value . "/" . $article->banner_image : "http://www.placehold.it/450x200/EFEFEF/AAAAAA&amp;text=no+image"; @endphp
            <label class="control-label col-md-3">Gambar Banner <span class="required">*</span></label>
            <div class="col-md-5">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail" style="width: 450px; height: 200px;">
                        <img src="{{ $banner_image_src }}" alt="" /> 
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 450px; max-height: 200px;"> </div>
                    <div>
                        <span class="btn default btn-file">
                            @if (!empty($article->banner_image))
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
        <div class="form-group">
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
    </div>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <button type="submit" class="btn green">Simpan</button>
                <a href="{{ url($menu) }}" class="btn btn-danger"><i class="fa fa-undo"></i> Kembali</a>
            </div>
        </div>
    </div>