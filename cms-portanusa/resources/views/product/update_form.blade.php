<div class="form-body">
    @if(Session::has('alert-error'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        {{ Session::get('alert-error') }}
    </div>
    @endif
    <span class="text-danger bold">( ) Wajib Diisi</span>
    <div class="form-group {{ ($errors->has('subject')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Subject
            <span class="required"> </span>
        </label>
        <div class="col-md-5">
            <select class="bs-select form-control" data-live-search="true" data-size="5" name="subject">
                @if(!empty($subjects))
                @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" {{ ($subject->id == $product->subject_id) ? "selected" : "" }}>{{ $subject->name }}</option>
                @endforeach
                @endif
            </select>
            @if ($errors->has('subject'))
            <span class="text-danger">{{ $errors->first('subject') }}</span>
            @endif
        </div>
    </div>
    <div class="form-group {{ ($errors->has('category')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Kategori
            <span class="required"> </span>
        </label>
        <div class="col-md-5">
            <select class="bs-select form-control" data-live-search="true" data-size="5" name="category">
                @if(!empty($categories))
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ ($category->id == $product->category_id) ? "selected" : "" }}>{{ $category->name }}</option>
                @endforeach
                @endif
            </select>
            @if ($errors->has('category'))
            <span class="text-danger">{{ $errors->first('category') }}</span>
            @endif
        </div>
    </div>
    <div class="form-group form-md-line-input {{ ($errors->has('name')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Nama Produk
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
    <div class="form-group form-md-line-input {{ ($errors->has('code')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Kode Produk
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
    <div class="form-group form-md-line-input  {{ ($errors->has('price')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Harga Produk
            <span class="required"> </span>
        </label>
        <div class="col-md-5">
            <input type="text" class="form-control" name="price" value="{{ (!empty($product)) ? number_format($product->price, 0, ',', '.') : old('price') }}" onkeypress="return isNumberKey(event);" onkeyup="formatCurr(this)">
            @if ($errors->has('price'))
            <span class="text-danger">{{ $errors->first('price') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group form-md-line-input  {{ ($errors->has('capital_price')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Harga Modal Produk
            <span class="required"> </span>
        </label>
        <div class="col-md-5">
            <input type="text" class="form-control" name="capital_price" value="{{ (!empty($product)) ? number_format($product->capital_price, 0, ',', '.') : old('price') }}" onkeypress="return isNumberKey(event);" onkeyup="formatCurr(this)">
            @if ($errors->has('capital_price'))
            <span class="text-danger">{{ $errors->first('capital_price') }}</span>
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
    <div class="periode" style="display: {{ ($product->stock_status_id == 2) ? "" : "none" }};">
        <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-5">
                <select class="form-control" name="pre_order_text" id="pre_order_text">
                    <option value="1" {{ ($product->pre_order_text == 1) ? "selected" : "" }}>1 Minggu</option>
                    <option value="2" {{ ($product->pre_order_text == 2) ? "selected" : "" }}>2 Minggu</option>
                    <option value="4" {{ ($product->pre_order_text == 4) ? "selected" : "" }}>4 Minggu</option>
                    <option value="8" {{ ($product->pre_order_text == 8) ? "selected" : "" }}>8 Minggu</option>
                </select>
                @if ($errors->has('pre_order_text'))
                <span class="text-danger">{{ $errors->first('pre_order_text') }}</span>
                @else
                <div class="form-control-focus"> </div>
                @endif
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
    <div class="form-group {{ ($errors->has('status')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Status
        </label>
        <div class="col-md-5">
            <select class="form-control" name="status" id="status">
                <option value="New" {{ ($product->status == 'New') ? "selected" : "" }}>New</option>
                <option value="Refurbished" {{ ($product->status == 'Refurbished') ? "selected" : "" }}>Refurbished</option>
            </select>
            @if ($errors->has('status'))
            <span class="text-danger">{{ $errors->first('status') }}</span>
            @endif
        </div>
    </div>
    <div class="form-group {{ ($errors->has('status_promo')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Status Promo
        </label>
        <div class="col-md-5">
            <select class="form-control" name="status_promo" id="status_promo">
                <option value="" {{ ($product->status_promo == Null) ? "selected" : "" }}>No Promo</option>
                <option value="Featured" {{ ($product->status_promo == 'Featured') ? "selected" : "" }}>Featured</option>
                <option value="On Sale" {{ ($product->status_promo == 'On Sale') ? "selected" : "" }}>On Sale</option>
                <option value="Best Rated" {{ ($product->status_promo == 'Best Rated') ? "selected" : "" }}>Best Rated</option>
            </select>
            @if ($errors->has('status_promo'))
            <span class="text-danger">{{ $errors->first('status_promo') }}</span>
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
        <label class="col-md-3 control-label">Produk Terkait</label>
        <div class="col-md-5">
            <select name="product_related[]" id="select2-button-addons-single-input-group-sm" class="form-control select-related" multiple style="width: 100%;">
                @if(!empty($product_relateds))
                @foreach($product_relateds as $product_related)
                <option value="{{ $product_related->related_id }}" selected="selected">{{ $product_related->name." ".$product_related->code }}</option>
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