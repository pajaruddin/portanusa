<div class="form-body">
    @if(Session::has('alert-error'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        {{ Session::get('alert-error') }}
    </div>
    @endif
    <div class="parent-text">
        <div class="content-discuss-parent">
            <div class="name">{{ $product_discuss->first_name }} 
                @if($product_discuss->publish === "T")
                    <span class="label label-success label-sm">Aktif</span>
                @else
                    <span class="label label-danger label-sm">Tidak Aktif</span>
                @endif
            </div>
            <div class="content-text">
                {{ $product_discuss->text }}
            </div>
            @if(count($child_discuss) > 0)
                @foreach($child_discuss as $discuss)
                    <div class="child-box">
                        @if($discuss->user_id === null)
                            <div class="child-name">
                                {{ $discuss->first_name }}
                                @if($discuss->publish === "T")
                                    <span class="label label-success label-sm">Aktif</span>
                                @else
                                    <span class="label label-danger label-sm">Tidak Aktif</span>
                                @endif
                            </div>
                        @else
                            <div class="child-name">
                                {{ $discuss->user_name }}
                                @if($discuss->publish === "T")
                                    <span class="label label-success label-sm">Aktif</span>
                                @else
                                    <span class="label label-danger label-sm">Tidak Aktif</span>
                                @endif
                            </div>
                        @endif
                        <div class="child-text">{{ $discuss->text }}</div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label">Pesan</label>
        <div class="col-md-6">
            <textarea class="form-control" name="text"></textarea>
            <div class="form-control-focus"> </div>
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