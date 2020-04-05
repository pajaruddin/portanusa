<div class="form-body">
    @if(Session::has('alert-error'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        {{ Session::get('alert-error') }}
    </div>
    @endif
    <span class="text-danger bold">(*) Wajib Diisi</span>

    <div class="form-group form-md-line-input {{ ($errors->has('name')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Nama Subject
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="text" class="form-control" maxlength="30" name="name" id="name" value="{{ (!empty($subject)) ? $subject->name : old('name') }}">
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
            <input type="checkbox" name="publish" class="make-switch" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" {{ (!empty($subject) && $subject->publish == "T") ? "checked" : "" }}>
        </div>
    </div>
    <div class="form-group">
        @php $subject_parent = !empty($subject->parent) ? $subject->parent : old('parent'); @endphp
        <label class="col-md-3 control-label">Parent Subject</label>
        <div class="col-md-6">
            <select name="parent" class="bs-select form-control" data-live-search="true" data-size="8">
                <option value="">- Tanpa Subject Parent -</option>
                @foreach ($parent_subject as $parent)
                    <option value="{{ $parent->id }}" {{ $subject_parent == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Deskripsi</label>
        <div class="col-md-8">
            <textarea class="form-control" name="description_indonesia">
                {{ (!empty($subject)) ? $subject->description : old('description') }}
            </textarea>
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Meta Deskripsi</label>
        <div class="col-md-6">
            <textarea class="form-control" name="meta_description">{{ (!empty($subject)) ? $subject->meta_description : old('meta_description') }}</textarea>
            <div class="form-control-focus"> </div>
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Meta Keyword</label>
        <div class="col-md-6">
            <textarea class="form-control" name="meta_keyword">{{ (!empty($subject)) ? $subject->meta_keyword : old('meta_keyword') }}</textarea>
            <div class="form-control-focus"> </div>
        </div>
    </div>
    {{-- <div class="form-group form-md-line-input {{ ($errors->has('url')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">URL
            <span class="required">*</span>
        </label>
        <div class="col-md-6">                                            
            <input type="text" class="form-control" name="url" value="{{ (!empty($subject)) ? $subject->url : old('url') }}" style="text-transform: lowercase;">            
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