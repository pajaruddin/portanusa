<div class="form-body">
    @if(Session::has('alert-error'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        {{ Session::get('alert-error') }}
    </div>
    @endif
    <span class="text-danger bold">( ) Wajib Diisi</span>
    <div class="form-group {{ ($errors->has('product')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Produk Base
            <span class="required"> </span>
        </label>
        <div class="col-md-5">
            <select class="bs-select form-control" data-live-search="true" data-size="5" name="product">
                @if(!empty($products))
                @foreach($products as $base)
                <option value="{{ $base->id }}" {{ ($base->id == $product_items->product_id) ? "selected" : "" }}>{{ $base->name }}</option>
                @endforeach
                @endif
            </select>
            @if ($errors->has('brand'))
            <span class="text-danger">{{ $errors->first('product') }}</span>
            @endif
        </div>
    </div>
    <div class="form-group form-md-line-input {{ ($errors->has('name')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Nama Package
            <span class="required"> </span>
        </label>
        <div class="col-md-5">
            <input type="text" class="form-control" name="name" value="{{ (!empty($product)) ? $product->name : old('name') }}">
            @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group form-md-line-input {{ ($errors->has('label')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Label Package
            <span class="required"> </span>
        </label>
        <div class="col-md-5">
            <input type="text" class="form-control" name="label" value="{{ (!empty($product_items)) ? $product_items->label : old('label') }}">
            @if ($errors->has('label'))
            <span class="text-danger">{{ $errors->first('label') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group form-md-line-input {{ ($errors->has('code')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Kode Package
            <span class="required"> </span>
        </label>
        <div class="col-md-5">
            <input type="text" class="form-control" name="code" value="{{ (!empty($product)) ? $product->code : old('code') }}">
            @if ($errors->has('code'))
            <span class="text-danger">{{ $errors->first('code') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Diskon Produk (%)</label>
        <div class="col-md-5">
            <input type="text" class="form-control" name="discount" value="{{ (!empty($product)) ? $product->discount : old('discount') }}" onkeypress="return decimalValue(event);">
            <div class="form-control-focus"> </div>
        </div>
    </div>
    <div class="form-group form-md-line-input {{ ($errors->has('weight')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Berat Produk (gram)
            <span class="required"> </span>
        </label>
        <div class="col-md-5">
            <input type="text" class="form-control" name="weight" onkeypress="return decimalValue(event);" value="{{ (!empty($product)) ? $product->weight : old('weight') }}">
            @if ($errors->has('weight'))
            <span class="text-danger">{{ $errors->first('weight') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Status Stok Produk</label>
        <div class="col-md-5">
            <select class="form-control" name="stock_status" id="stock-status">
                @if(!empty($stock_status))
                @foreach($stock_status as $status)
                <option value="{{ $status->id }}" {{ ($status->id == $product->stock_status_id) ? "selected" : "" }}>{{ $status->name }}</option>
                @endforeach
                @endif
            </select>
            <div class="form-control-focus"> </div>
        </div>
    </div>
    <div class="periode" style="display: {{ ($product->stock_status_id == 2) ? "" : "none" }};">
        <div class="form-group">
            <label class="col-md-3 control-label">Periode Ketersediaan</label>
            <div class="col-md-5">
                <div class="input-group input-large date-picker input-daterange" data-date-format="dd M yyyy">
                    <input type="text" name="date_start_periode" class="form-control" value="{{ (!empty($product->date_start_periode)) ? date('d M Y', strtotime($product->date_start_periode)) : old('date_start_periode') }}">
                    <span class="input-group-addon"> to </span>
                    <input type="text" name="date_end_periode" class="form-control" value="{{ (!empty($product->date_end_periode)) ? date('d M Y', strtotime($product->date_end_periode)) : old('date_end_periode') }}">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group {{ ($errors->has('highlight_indonesia')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Highlight Produk
            <span class="required"> </span>
        </label>
        <div class="col-md-8">
            <textarea class="text-area" id="highlight_indonesia" name="highlight_indonesia">{{ (!empty($product)) ? $product->highlight : old('highlight') }}</textarea>
            @if ($errors->has('highlight_indonesia'))
            <span class="text-danger">{{ $errors->first('highlight_indonesia') }}</span>
            @endif
        </div>
    </div>
    <div class="form-group {{ ($errors->has('description_indonesia')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Deskripsi Produk
            <span class="required"> </span>
        </label>
        <div class="col-md-8">
            <textarea class="text-area" id="description_indonesia" name="description_indonesia">{{ (!empty($product)) ? $product->description : old('description') }}</textarea>
            @if ($errors->has('description_indonesia'))
            <span class="text-danger">{{ $errors->first('description_indonesia') }}</span>
            @endif
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Meta Deskripsi</label>
        <div class="col-md-5">
            <textarea class="form-control" name="meta_description">{{ (!empty($product)) ? $product->meta_description : old('meta_description') }}</textarea>
            <div class="form-control-focus"> </div>
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Meta Keyword</label>
        <div class="col-md-5">
            <textarea class="form-control" name="meta_keyword">{{ (!empty($product)) ? $product->meta_keyword : old('meta_keyword') }}</textarea>
            <div class="form-control-focus"> </div>
        </div>
    </div>
    <div class="form-group" style="height: 30px;">
        <label class="control-label col-md-3">Publish</label>
        <div class="col-md-9">
            <input type="checkbox" name="publish" class="make-switch" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" {{ ($product->publish == "T") ? "checked" : "" }}>
        </div>
    </div>
    <div class="form-group" style="height: 30px;">
        <label class="control-label col-md-3">Fungsi Order</label>
        <div class="col-md-9">
            <input type="checkbox" name="able_to_order" class="make-switch" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" {{ ($product->able_to_order == "T") ? "checked" : "" }}>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Produk Composition
            <span class="required"> </span>
        </label>
        <div class="col-md-5">
            <select name="product_composition[]" id="select2-button-addons-single-input-group-sm" class="form-control select-related" multiple style="width: 100%;">
                @if(!empty($product_compositions))
                @foreach($product_compositions as $product_composition)
                <option value="{{ $product_composition->product_id }}" selected="selected">{{ $product_composition->name." ".$product_composition->code }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <h3 class="block">Gambar Unit</h3>
    <div class="mt-repeater mt-repeater-image" style="padding-left: 15px;">
        <div data-repeater-list="unit_image">
            <div data-repeater-item class="form-group mt-repeater-item" style="display: none;">
                <div class="mt-repeater-input">
                    <label class="control-label">Gambar</label>
                    <br/>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 150px; height: 150px;">
                            <img src="http://www.placehold.it/150x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" /> 
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 150px; max-height: 150px;"> </div>
                        <div>
                            <span class="btn default btn-file">
                                <span class="fileinput-new"> Pilih Gambar </span>
                                <span class="fileinput-exists"> Ubah </span>
                                <input type="file" class="unit_image" name="file" onchange="validateImage(this);">
                            </span>
                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                        </div>
                    </div>
                    <div class="clearfix margin-top-10 margin-bottom-10">
                        <span class="text-danger" id="image-unit-error"></span>
                    </div>
                </div>
                <div class="mt-repeater-input">
                    <label class="control-label">Posisi</label>
                    <br/>
                    <input type="hidden" name="id" value="">
                    <input type="text" name="position" class="form-control mt-repeater-input-inline position" onkeypress="return isNumberKey(event);">
                </div>
                <div class="mt-repeater-input">
                    <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete">
                        <i class="fa fa-close"></i> Hapus
                    </a>
                </div>
            </div>
            @if(!empty($product_images))
            @foreach($product_images as $product_image)
            <div data-repeater-item class="form-group mt-repeater-item" id="{{ $product_image->id }}">
                <div class="mt-repeater-input">
                    <label class="control-label">Gambar</label>
                    <br/>
                    @php $unit_image = (!empty($product_image->image)) ? AppConfiguration::assetPortalDomain()->value.  "/" . AppConfiguration::productImagePath()->value . "/" . $product_image->image : "http://www.placehold.it/150x150/EFEFEF/AAAAAA&amp;text=no+image"; @endphp
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 150px; height: 150px;">
                            <img src="{{ $unit_image }}" alt="" /> 
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 150px; max-height: 150px;"> </div>
                        <div>
                            <span class="btn default btn-file">
                                <span class="fileinput-new"> Ubah Gambar </span>
                                <span class="fileinput-exists"> Ubah </span>
                                <input type="file" class="unit_image" name="file" onchange="validateImage(this);">
                            </span>
                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                        </div>
                    </div>
                    <div class="clearfix margin-top-10 margin-bottom-10">
                        <span class="text-danger" id="image-unit-error"></span>
                    </div>
                </div>
                <div class="mt-repeater-input">
                    <label class="control-label">Posisi</label>
                    <br/>
                    <input type="hidden" name="id" value="{{ $product_image->id }}">
                    <input type="text" name="position" class="form-control mt-repeater-input-inline position" onkeypress="return isNumberKey(event);" value="{{ $product_image->position }}">
                </div>
                <div class="mt-repeater-input">
                    <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete">
                        <i class="fa fa-close"></i> Hapus
                    </a>
                </div>
            </div>
            @endforeach
            @endif
        </div>
        <a href="javascript:;" data-repeater-create class="btn btn-success mt-repeater-add">
            <i class="fa fa-plus"></i> Tambah
        </a>
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