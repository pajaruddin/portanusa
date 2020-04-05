<div class="form-body">
    @if(Session::has('alert-error'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        {{ Session::get('alert-error') }}
    </div>
    @endif
    <span class="text-danger bold">(*) Wajib Diisi</span>

    <div class="form-group">
        <label class="control-label col-md-3">Bulan
            <span class="required">*</span>
        </label>
        <div class="col-md-3">
            <div class="input-group right-addon date-start">
                <input type="text" name="export_date" id="export_date" class="form-control" readonly="">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
            @if ($errors->has('date_start'))
            <span class="text-danger">{{ $errors->first('date_start') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label">Status
            <span class="required">*</span>
        </label>
        <div class="col-md-5">
            <select class="bs-select form-control" data-live-search="true" data-size="5" name="status">
                <option value="">- Pilih Status -</option>
                @if(!empty($order_status))
                @foreach($order_status as $status)
                <option value="{{ $status->id }}">{{ $status->status }}</option>
                @endforeach
                @endif
            </select>
            <span class="text-danger" id="status-error"></span>
        </div>
    </div>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" class="btn green">Export</button>
            <a href="{{ url($menu) }}" class="btn btn-danger"><i class="fa fa-undo"></i> Kembali</a>
        </div>
    </div>
</div>