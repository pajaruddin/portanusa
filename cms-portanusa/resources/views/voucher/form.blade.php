<div class="form-body">
    @if(Session::has('alert-error'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        {{ Session::get('alert-error') }}
    </div>
    @endif
    <span class="text-danger bold">(*) Wajib Diisi</span>

    <div class="form-group form-md-line-input {{ ($errors->has('name')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Nama Voucher
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="name" id="name" value="{{ (!empty($voucher)) ? $voucher->name : old('name') }}">
            @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group form-md-line-input {{ ($errors->has('code')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Code Voucher
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="code" id="code" value="{{ (!empty($voucher)) ? $voucher->code : old('code') }}">
            @if ($errors->has('code'))
            <span class="text-danger">{{ $errors->first('code') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group form-md-line-input  {{ ($errors->has('minimum_amount')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Minumum Pembayaran
            <span class="required">*</span>
        </label>
        <div class="col-md-5">
            <input type="text" class="form-control" name="minimum_amount" value="{{ (!empty($voucher)) ? number_format($voucher->minimum_amount, 0, ',', '.') : old('minimum_amount') }}" onkeypress="return isNumberKey(event);" onkeyup="formatCurr(this)">
            @if ($errors->has('minimum_amount'))
            <span class="text-danger">{{ $errors->first('minimum_amount') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Diskon (%)
            <span class="required">*</span>
        </label>
        <div class="col-md-5">
            <input type="text" class="form-control" name="discount" value="{{ (!empty($voucher)) ? $voucher->discount : old('discount') }}" onkeypress="return decimalValue(event);">
            <div class="form-control-focus"> </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Deskripsi
            <span class="required">*</span>
        </label>
        <div class="col-md-8">
            <textarea class="form-control" name="description_indonesia">
                {{ (!empty($voucher)) ? $voucher->description : old('description') }}
            </textarea>
        </div>
    </div>
    <div class="form-group last">
        @php $banner_image_src = !empty($voucher->banner) ? AppConfiguration::assetPortalDomain()->value.  "/" . AppConfiguration::voucherBannerImagePath()->value . "/" . $voucher->banner : "http://www.placehold.it/450x200/EFEFEF/AAAAAA&amp;text=no+image"; @endphp
        <label class="control-label col-md-3">Gambar Banner <span class="required">*</span></label>
        <div class="col-md-5">
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 450px; height: 200px;">
                    <img src="{{ $banner_image_src }}" alt="" /> 
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 450px; max-height: 200px;"> </div>
                <div>
                    <span class="btn default btn-file">
                        @if (!empty($voucher->banner))
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
            @if ($errors->has('banner'))
            <div class="clearfix margin-top-10">
                <span class="text-danger">
                    {{ $errors->first('banner') }}
                </span>
            </div>
            @endif
        </div>
    </div>
    <div class="form-group {{ ($errors->has('date_start') or $errors->has('date_end')) ? "has-error" : "" }}">
        <label class="control-label col-md-3">Periode
            <span class="required">*</span>
        </label>
        <div class="col-md-3">
            <div class="input-group right-addon date-start">
                @php $date_start = (!empty($voucher)) ? date('d M Y H:i:s', strtotime($voucher->date_start)) : old('date_start'); @endphp
                <input type="text" name="date_start" class="form-control" value="{{ $date_start }}" readonly="">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
            @if ($errors->has('date_start'))
            <span class="text-danger">{{ $errors->first('date_start') }}</span>
            @endif
        </div>
        <div class="col-md-3">
            <div class="input-group right-addon date-end">
                @php $date_end = (!empty($voucher)) ? date('d M Y H:i:s', strtotime($voucher->date_end)) : old('date_end'); @endphp
                <input type="text" name="date_end" class="form-control" value="{{ $date_end }}" readonly="">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
            @if ($errors->has('date_end'))
            <span class="text-danger">{{ $errors->first('date_end') }}</span>
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