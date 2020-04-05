<div class="form-body">
    @if(Session::has('alert-error'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        {{ Session::get('alert-error') }}
    </div>
    @endif
    <span class="text-danger bold">(*) Wajib Diisi</span>

    <div class="form-group form-md-line-input {{ ($errors->has('title')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Judul
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="text" class="form-control" maxlength="30" name="title" id="title" value="{{ (!empty($video)) ? $video->title : old('title') }}">
            @if ($errors->has('title'))
            <span class="text-danger">{{ $errors->first('title') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Video URL
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="embed_url" value="{{ (!empty($video)) ? $video->embed_url : old('embed_url') }}">
            <div class="form-control-focus"> </div>
        </div>
    </div>
    <div class="form-group" style="height: 30px;">
        <label class="control-label col-md-3">Publish</label>
        <div class="col-md-9">
            <input type="checkbox" name="publish" class="make-switch" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" {{ (!empty($video) && $video->publish == "T") ? "checked" : "" }}>
        </div>
    </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Deskripsi</label>
        <div class="col-md-8">
            <textarea class="form-control" name="description_indonesia">
                {{ (!empty($video)) ? $video->description : old('description') }}
            </textarea>
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