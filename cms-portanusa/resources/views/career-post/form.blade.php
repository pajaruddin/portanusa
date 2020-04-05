<div class="form-body">
        @if(Session::has('alert-error'))
        <div class="alert alert-danger">
            <button class="close" data-close="alert"></button>
            {{ Session::get('alert-error') }}
        </div>
        @endif
        <span class="text-danger bold">(*) Wajib Diisi</span>
        <div class="form-group form-md-line-input {{ ($errors->has('position')) ? "has-error" : "" }}">
            <label class="col-md-3 control-label">Posisi
                <span class="required">*</span>
            </label>
            <div class="col-md-6">
                <input type="text" class="form-control" maxlength="30" name="position" id="position" value="{{ (!empty($career)) ? $career->position : old('position') }}">
                @if ($errors->has('title'))
                <span class="text-danger">{{ $errors->first('position') }}</span>
                @else
                <div class="form-control-focus"> </div>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Job Deskripsi
                <span class="required">*</span>
            </label>
            <div class="col-md-8">
                <textarea class="form-control" name="job_description">
                    {{ (!empty($career)) ? $career->job_description : old('job_description') }}
                </textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Job Requirement
            <span class="required">*</span>
            </label>
            <div class="col-md-8">
                <textarea class="form-control" name="job_requirement">
                    {{ (!empty($career)) ? $career->job_requirement : old('job_requirement') }}
                </textarea>
            </div>
        </div>
        <div class="form-group" style="height: 30px;">
            <label class="control-label col-md-3">Publish</label>
            <div class="col-md-9">
                <input type="checkbox" name="publish" class="make-switch" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" {{ (!empty($career) && $career->publish == "T") ? "checked" : "" }}>
            </div>
        </div>
        <div class="form-group {{ ($errors->has('date_start') or $errors->has('date_end')) ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Periode
            <span class="required">*</span>
        </label>
        <div class="col-md-3">
            <div class="input-group right-addon date-start">
                @php $date_start = (!empty($career)) ? date('d M Y H:i:s', strtotime($career->date_start)) : old('date_start'); @endphp
                <input type="text" name="date_start" class="form-control" value="{{ $date_start }}">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
            @if ($errors->has('date_start'))
            <span class="text-danger">{{ $errors->first('date_start') }}</span>
            @endif
        </div>
        <div class="col-md-3">
            <div class="input-group right-addon date-end">
                @php $date_end = (!empty($career)) ? date('d M Y H:i:s', strtotime($career->date_end)) : old('date_end'); @endphp
                <input type="text" name="date_end" class="form-control" value="{{ $date_end }}">
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