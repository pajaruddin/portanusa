<div class="form-body">
    @if(Session::has('alert-error'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        {{ Session::get('alert-error') }}
    </div>
    @endif
    <span class="text-danger bold">( ) Wajib Diisi</span>
    <div class="form-group">
        <label class="col-md-3 control-label">Produk Base
            <span class="required"> </span>
        </label>
        <div class="col-md-5">
            <select class="bs-select form-control" data-live-search="true" data-size="5" name="product">
                @if(!empty($products))
                @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
                @endif
            </select>
            <span class="text-danger" id="product-error"></span>
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Nama Package
            <span class="required"> </span>
        </label>
        <div class="col-md-5">
            <input type="text" class="form-control" name="name">
            <div class="form-control-focus"></div>
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Label Package
            <span class="required"> </span>
        </label>
        <div class="col-md-5">
            <input type="text" class="form-control" name="label">
            <div class="form-control-focus"></div>
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Kode Package
            <span class="required"> </span>
        </label>
        <div class="col-md-5">
            <input type="text" class="form-control" name="code">
            <div class="form-control-focus"> </div>
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Diskon Produk (%)</label>
        <div class="col-md-5">
            <input type="text" class="form-control" name="discount" onkeypress="return decimalValue(event);">
            <div class="form-control-focus"> </div>
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Berat Produk (gram)
            <span class="required"> </span>
        </label>
        <div class="col-md-5">
            <input type="text" class="form-control" name="weight" onkeypress="return decimalValue(event);">
            <div class="form-control-focus"> </div>
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Status Stok Produk</label>
        <div class="col-md-5">
            <select class="form-control" name="stock_status" id="stock-status">
                @if(!empty($stock_status))
                @foreach($stock_status as $status)
                <option value="{{ $status->id }}">{{ $status->name }}</option>
                @endforeach
                @endif
            </select>
            <div class="form-control-focus"> </div>
        </div>
    </div>
    <div class="periode" style="display: none;">
        <div class="form-group">
            <label class="col-md-3 control-label">Periode Ketersediaan</label>
            <div class="col-md-5">
                <div class="input-group input-large date-picker input-daterange" data-date-format="dd M yyyy">
                    <input type="text" name="date_start_periode" class="form-control">
                    <span class="input-group-addon"> to </span>
                    <input type="text" name="date_end_periode" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group form-brief">
        <label class="col-md-3 control-label">Highlight Produk
            <span class="required"> </span>
        </label>
        <div class="col-md-8">
            <textarea class="text-area" id="highlight_indonesia" name="highlight_indonesia"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Deskripsi Produk
            <span class="required"> </span>
        </label>
        <div class="col-md-8">
            <textarea class="text-area" name="description_indonesia"></textarea>
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Meta Deskripsi</label>
        <div class="col-md-5">
            <textarea class="form-control" name="meta_description"></textarea>
            <div class="form-control-focus"> </div>
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Meta Keyword</label>
        <div class="col-md-5">
            <textarea class="form-control" name="meta_keyword"></textarea>
            <div class="form-control-focus"> </div>
        </div>
    </div>
    <div class="form-group" style="height: 30px;">
        <label class="control-label col-md-3">Publish</label>
        <div class="col-md-9">
            <input type="checkbox" name="publish" class="make-switch" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" checked="">
        </div>
    </div>
    <div class="form-group" style="height: 30px;">
        <label class="control-label col-md-3">Fungsi Order</label>
        <div class="col-md-9">
            <input type="checkbox" name="able_to_order" class="make-switch" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" checked="">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Produk Composition
            <span class="required"> </span>
        </label>
        <div class="col-md-5">
            <select name="product_composition[]" id="select2-button-addons-single-input-group-sm" class="form-control select-related" multiple style="width: 100%;">
    
            </select>
        </div>
    </div>
    <h3 class="block">Gambar Unit</h3>
    <div class="mt-repeater" style="padding-left: 15px;">
        <div data-repeater-list="unit_image">
            <div data-repeater-item class="form-group mt-repeater-item" style="display: none;">
                <div class="mt-repeater-input">
                    <label class="control-label">Gambar
                        <span class="required"> </span>
                    </label>
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
                    <label class="control-label">Posisi
                        <span class="required"> </span>
                    </label>
                    <br/>
                    <input type="text" name="position" class="form-control mt-repeater-input-inline position" onkeypress="return isNumberKey(event);">
                </div>
                <div class="mt-repeater-input">
                    <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete">
                        <i class="fa fa-close"></i> Hapus
                    </a>
                </div>
            </div>
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