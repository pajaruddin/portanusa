<!-- Modal -->
<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="transferModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <form enctype="multipart/form-data" method="POST" role="form" id="transferForm" action="{{ url('transfer-image') }}" autocomplete="off">
        {{ csrf_field() }}
        <div class="modal-body">

            <div class="alert alert-success alert-dismissible fade show" id="success_message" role="alert" style="display: none;">

            </div>

            <div class="alert alert-danger alert-dismissible fade show" id="error_message" role="alert" style="display: none;">

            </div>

            <div class="alert alert-info alert-dismissible fade show" role="alert">
                upload your transfer image here
            </div>

            <div class="form-group">
                <input type="hidden" name="id" id="text-order-id" />
                <input type="hidden" name="invoice_no" id="text-invoice-no" />
                <input type="file" name="transfer_image" id="transfer-file" required class="form-control" />
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            <button type="submit" id="transfer_submit" class="btn btn-primary btn-sm">Submit</button>
        </div>
        </form>
    </div>
    </div>
</div>

@push('plugin_scripts')
<script src="/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="/plugins/jquery-form/jquery.form.min.js"></script>
@endpush

@push('custom_scripts')
<script>
    
    $(function(){
        $('#transfer-file').change(function(){
            validateImage(this);
        });
        $( "#transferForm" ).validate( {
            rules: {
                transfer_image: "required",
            },
            messages: {
                transfer_image: "Please enter your transfer image"
            },
            errorElement: "h6",
            errorClass: "text-error",
            submitHandler: function(form) {
            $('#transfer_submit').attr('disabled','disabled');
            $('#transfer_submit').html('Loading...');
            $('#transferForm').ajaxSubmit({
                dataType: 'json',
                success: function (data) {
                    if (data.status == 'error') {
                        $("#error_message").html(data.message);
                        $("#error_message").show();
                        $("#error_message").fadeOut(6000);
                    } else {
                        $("#success_message").html(data.message);
                        $("#success_message").show();
                        setTimeout(function () {
                            $("#success_message").fadeOut(3000);
                            $("#transferForm")[0].reset();
                            location.reload();
                        }, 1000);
                    }
                    $('#transfer_submit').removeAttr('disabled');
                    $('#transfer_submit').html('Submit');
                }
            });
            return false;
            }
        });

    });

    var _validFileExtensions = [".jpg", ".jpeg", ".png"];

    function validateImage(oInput) {
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
                        text: 'Image file format is not allowed',
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
@endpush
