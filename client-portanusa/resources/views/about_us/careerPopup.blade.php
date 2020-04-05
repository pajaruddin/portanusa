<div class="modal fade" id="careerModal" tabindex="-1" role="dialog" aria-labelledby="careerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="careerModalLabel">Complete Your Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/about_us/form" id="careerForm" method="POST" autocomplete="off" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <select class="form-control ml-0" name="career_post_id">
                            <option value="">Choose the position you want</option>
                            @if(count($career_posts) != 0)
                            @foreach($career_posts as $post)
                            <option value="{{ $post->id }}">{{ $post->position }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="full_name" class="form-control" placeholder="Full Name" />
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-6">
                        <input type="text" name="phone" class="form-control" placeholder="Phone" onkeypress="return isNumberKey(event)" maxlength="12" />
                    </div>
                    <div class="col-sm-6">
                        <input type="email" name="email" class="form-control" placeholder="Email" />
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <textarea class="form-control" name="address" placeholder="Address" rows="4"></textarea>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-3">
                        <h6 class="font-weight-light">CV File ( .pdf )</h6>
                    </div>
                    <div class="col-sm">
                        <input type="file" name="cv_file" class="form-control" onchange="validateFile(this)" required />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>

@push('plugin_scripts')
<script src="/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="/plugins/currency/currency.js"></script>
@endpush

@push('custom_scripts')
<script>
    $(function(){

        $( "#careerForm" ).validate( {
            rules: {
                career_post_id: "required",
                full_name: "required",
                phone: "required",
                email: {
                    required: true,
                    email: true
                },
                address: "required"
            },
            messages: {
                career_post_id: "Please choose the position",
                full_name: "Please enter your full name",
                phone: "Please enter your phone number",
                email: {
                    required: "Please enter your email",
                    email: "Please enter a valid email address",
                },
                address: "Please enter your address"
            },
            errorElement: "h6",
            errorClass: "text-error",
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            }
        });

    });
    var _validFileExtensions = [".pdf"];
    function validateFile(oInput) {
        if (oInput.type == "file") {
            var sFileName = oInput.value;
            if (sFileName.length > 0) {
                var blnValid = false;
                for (var j = 0; j < _validFileExtensions.length; j++) {
                    var sCurExtension = _validFileExtensions[j];
                    if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                        blnValid = true;
                        break;
                    }
                }
                if (!blnValid) {
                    new Noty({
                        type: 'error',
                        text: 'File format is not allowed',
                        layout: 'center',
                        timeout: 2000,
                        modal: true
                    }).show();
                    oInput.value = "";
                    return false;
                }
            }
        }
        return true;
    }
</script>
@if (session('success'))
<script>
	$(function(){
		new Noty({
			type: "success",
			text: "{{ session('success') }}",
			layout: 'center',
			timeout: 2000,
			modal: true
		}).show();
	});
</script>
@endif
@if (session('failed'))
<script>
	$(function(){
		new Noty({
			type: "error",
			text: "{!! session('failed') !!}",
			layout: 'center',
			timeout: 2000,
			modal: true
		}).show();
	});
</script>
@endif
@endpush