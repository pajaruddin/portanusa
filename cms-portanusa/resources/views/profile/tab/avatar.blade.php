<form action="{{ url("/profile/avatar") }}" method="POST" role="form" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="form-group">
        @php $avatar_src = (!empty(Auth::user()->photo_image)) ? AppConfiguration::assetPortalDomain()->value.  "/" . AppConfiguration::avatarImagePath()->value . "/" . Auth::user()->photo_image : "http://www.placehold.it/250x250/EFEFEF/AAAAAA&amp;text=no+image"; @endphp
        <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-new thumbnail" style="width: 250px; height: 250px;">
                <img src="{{ $avatar_src }}" alt="" />
            </div>
            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 250px; max-height: 250px;"> </div>
            <div>
                <span class="btn default btn-file">
                    <span class="fileinput-new"> Ubah Gambar </span>                            
                    <span class="fileinput-exists"> Ubah Gambar</span>
                    <input type="file" name="photo_image" onchange="return validateImage(this);"> </span>
                <a href="javascript:;" class="btn default fileinput-exists red" data-dismiss="fileinput"> Batalkan </a>
            </div>
        </div>
        <div class="clearfix margin-top-10 margin-bottom-10">
            <span class="label label-danger">NOTE!</span> Format gambar harus JPG atau PNG.
        </div>
        @if ($errors->has('photo_image'))
        <div class="clearfix margin-top-10">
            <span class="text-danger">
                {{ $errors->first('photo_image') }}
            </span>
        </div>
        @endif
    </div>
    <div class="margin-top-10">
        <button type="submit" class="btn green">Simpan</button>
    </div>
</form>