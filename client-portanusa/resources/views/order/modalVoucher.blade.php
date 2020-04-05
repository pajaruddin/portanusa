<!-- Modal -->
<div class="modal fade voucher-modal" id="voucherModal" tabindex="-1" role="dialog" aria-labelledby="voucherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="voucherModalLabel">Checkout</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success alert-dismissible fade show" id="success_message_voucher" role="alert" style="display: none;">

                </div>
    
                <div class="alert alert-danger alert-dismissible fade show" id="error_message_voucher" role="alert" style="display: none;">
    
                </div>
                <form id="voucherForm" action="/voucher/check" method="POST" autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" name="total_price" value="{{ $total_price }}" />
                    <div class="form-group">
                        <label for="name-input">Input voucher code</label>
                        <input type="text" name="voucher" class="form-control">
                    </div>
                    <div class="form-group pt-2">
                        <button type="submit" id="voucher-submit" class="btn btn-primary w-100">Submit</button>
                    </div>
                </form>
            </div>
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
    $( "#voucherForm" ).validate( {
    rules: {
        voucher: "required",
    },
    messages: {
        voucher: "Please enter your voucher",
    },
    errorElement: "h6",
    errorClass: "text-error",
    submitHandler: function(form) {
        $('#voucher-submit').attr('disabled','disabled');
        $('#voucher-submit').html('Loading...');
        $('#voucherForm').ajaxSubmit({
            dataType: 'json',
            success: function (data) {
                if (data.status == 'error') {
                    $("#error_message_voucher").html(data.message);
                    $("#error_message_voucher").show();
                    $("#error_message_voucher").fadeOut(6000);
                } else {
                    $("#success_message_voucher").html(data.message);
                    $("#success_message_voucher").show();
                    setTimeout(function () {
                        $("#voucherModal").modal('hide');
                        $("#success_message_voucher").fadeOut(3000);
                        $("#voucherForm")[0].reset();
                        location.reload();
                    }, 1000);
                }
                $('#voucher-submit').removeAttr('disabled');
                $('#voucher-submit').html('Submit');
            }
        });
        return false;
    }
    });

});
</script>
@endpush