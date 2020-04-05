<div class="form-body">
    @if(Session::has('alert-error'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        {{ Session::get('alert-error') }}
    </div>
    @endif
    <span class="text-danger bold">(*) Wajib Diisi</span>

    <div class="form-group form-md-line-input {{ ($errors->has('name')) ? 'has-error' : '' }}">
        <label class="col-md-3 control-label">Nama Sale Event
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="text" class="form-control" maxlength="30" name="name" id="name" value="{{ (!empty($sale_event)) ? $sale_event->name : old('name') }}">
            @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group {{ ($errors->has('date_start') or $errors->has('date_end')) ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Periode
            <span class="required">*</span>
        </label>
        <div class="col-md-3">
            <div class="input-group right-addon date-start">
                @php $date_start = (!empty($sale_event)) ? date('d M Y H:i:s', strtotime($sale_event->date_start)) : old('date_start'); @endphp
                <input type="text" name="date_start" class="form-control" value="{{ $date_start }}" readonly="">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
            @if ($errors->has('date_start'))
            <span class="text-danger">{{ $errors->first('date_start') }}</span>
            @endif
        </div>
        <div class="col-md-3">
            <div class="input-group right-addon date-end">
                @php $date_end = (!empty($sale_event)) ? date('d M Y H:i:s', strtotime($sale_event->date_end)) : old('date_end'); @endphp
                <input type="text" name="date_end" class="form-control" value="{{ $date_end }}" readonly="">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
            @if ($errors->has('date_end'))
            <span class="text-danger">{{ $errors->first('date_end') }}</span>
            @endif
        </div>
    </div>
    <div class="form-group last">
        @php $banner_src = !empty($sale_event->banner_image) ? AppConfiguration::assetPortalDomain()->value.  "/" . AppConfiguration::saleEventBannerImagePath()->value . "/" . $sale_event->banner_image : "http://www.placehold.it/450x200/EFEFEF/AAAAAA&amp;text=no+image"; @endphp
        <label class="control-label col-md-3">Gambar Banner <span class="required">*</span></label>
        <div class="col-md-5">
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 450px; height: 200px;">
                    <img src="{{ $banner_src }}" alt="" /> 
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 450px; max-height: 200px;"> </div>
                <div>
                    <span class="btn default btn-file">
                        @if (!empty($sale_event->banner_image))
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
            <textarea class="form-control" name="meta_description">{{ (!empty($sale_event)) ? $sale_event->meta_description : old('meta_description') }}</textarea>
            <div class="form-control-focus"> </div>
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Meta Keyword</label>
        <div class="col-md-6">
            <textarea class="form-control" name="meta_keyword">{{ (!empty($sale_event)) ? $sale_event->meta_keyword : old('meta_keyword') }}</textarea>
            <div class="form-control-focus"> </div>
        </div>
    </div>
    <div class="form-group" style="height: 30px;">
        <label class="control-label col-md-3">Aktif</label>
        <div class="col-md-9">
            <input type="checkbox" name="enable" class="make-switch" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" {{ (!empty($sale_event) && $sale_event->enable == "T") ? "checked" : "" }}>
        </div>
    </div>
    <h3 class="block">Produk</h3>
    <div class="form-group">
        <label class="col-md-2 control-label">Produk</label>
        <div class="col-md-7">
            <select id="select2-button-addons-single-input-group-sm" class="form-control select-product" style="width: 100%;">

            </select>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn blue" id="add-product">Tambah</button>
        </div>
    </div>
    <hr/>
    <table class="table table-bordered table-hover table-striped" id="tbl-product">
        <thead>
            <tr>
                <th style="width: 40%">Produk</th>
                <th style="width: 20%">Harga</th>
                <th style="width: 20%">Diskon (%)</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($sale_event_products))
            @foreach ($sale_event_products as $product_sale_event)
            <tr id="row_sl_{{ $loop->index }}">
        <input type="hidden" id="sale_event_product_{{ $loop->index }}" name="product_sale_event[{{ $loop->index }}][id]" value="{{ $product_sale_event->id }}">
        <td>
            <input type="hidden" id="product_id_{{ $loop->index }}" name="product_sale_event[{{ $loop->index }}][product_id]" value="{{ $product_sale_event->product_id }}">
            {{ $product_sale_event->name." ".$product_sale_event->code }}
        </td>
        <td>{{ number_format($product_sale_event->price, 0, ',', '.') }}</td>
        <td>
            <input type="text" class="form-control" name="product_sale_event[{{ $loop->index }}][discount]" value="{{ $product_sale_event->discount }}">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-danger" onclick="deleteProductItemRow({{ $loop->index }})">
                <i class="fa fa-close"></i>
            </button>
        </td>
        </tr>
        @endforeach
        @endif
        </tbody>
    </table>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" class="btn green">Simpan</button>
            <a href="{{ url($menu) }}" class="btn btn-danger"><i class="fa fa-undo"></i> Kembali</a>
        </div>
    </div>
</div>