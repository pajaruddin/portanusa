<div class="form-body">
    @if(Session::has('alert-error'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        {{ Session::get('alert-error') }}
    </div>
    @endif
    <span class="text-danger bold">(*) Wajib Diisi</span>

    <div class="form-group form-md-line-input {{ ($errors->has('title')) ? "has-error" : "" }}">
        <label class="col-md-3 control-label">Title
            <span class="required">*</span>
        </label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="title" id="title" value="{{ (!empty($catalog)) ? $catalog->title : old('title') }}">
            @if ($errors->has('title'))
            <span class="text-danger">{{ $errors->first('title') }}</span>
            @else
            <div class="form-control-focus"> </div>
            @endif
        </div>
    </div>
    <div class="form-group last">
        @php $name_file = !empty($catalog->file_catalog) ? $catalog->file_catalog : ""; @endphp
        <label class="control-label col-md-3">File Catalog <span class="required">*</span></label>
        <div class="col-md-5">
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="border: none !important">
                    {{ $name_file }}
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="border: none !important"> </div>
                <div>
                    <span class="btn default btn-file">
                        @if (!empty($catalog->file_catalog))
                        <span class="fileinput-new"> Ubah File </span>                            
                        @else
                        <span class="fileinput-new"> Pilih File </span>                            
                        @endif
                        <span class="fileinput-exists"> Ubah File</span>
                        <input type="file" name="file_catalog" value="{{ (!empty($catalog)) ? $catalog->file_catalog : old('file_catalog') }}" onchange="validateImage(this);">
                    </span>
                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput">Batalkan</a>
                </div>
            </div>
            <div class="clearfix margin-top-10">
                <span class="label label-danger">NOTE!</span> Format File PDF.
            </div>
            @if ($errors->has('file_catalog'))
            <div class="clearfix margin-top-10">
                <span class="text-danger">
                    {{ $errors->first('file_catalog') }}
                </span>
            </div>
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