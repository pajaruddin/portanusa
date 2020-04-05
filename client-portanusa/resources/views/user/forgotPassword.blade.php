<!-- Modal -->
<div class="modal fade" id="forgotModal" tabindex="-1" role="dialog" aria-labelledby="forgotModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="forgotModalLabel"><i class="fa fa-question-circle"></i> Forgot Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" role="form" id="forgotForm" action="{{ url('forgot-password') }}" autocomplete="off">
        {{ csrf_field() }}
        <div class="modal-body">

            <div class="alert alert-success alert-dismissible fade show" id="success_message" role="alert" style="display: none;">

            </div>

            <div class="alert alert-danger alert-dismissible fade show" id="error_message" role="alert" style="display: none;">

            </div>

            <div class="alert alert-info alert-dismissible fade show" role="alert">
              Input your email. The system will send a link to your email to reset your password.
            </div>

            <div class="form-group">
              <label for="email-input">Email</label>
              <input type="email" name="email" class="form-control" id="emailForgot" placeholder="Email">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          <button type="submit" id="forgot_password_submit" class="btn btn-primary btn-sm">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('custom_scripts')
<script>
  
  $(function(){
    $( "#forgotForm" ).validate( {
      rules: {
        email: {
          required: true,
          email: true
        }
      },
      messages: {
        email: {
            required: "Please enter your email",
            email: "Please enter a valid email address",
        },
      },
      errorElement: "h6",
      errorClass: "text-error",
      submitHandler: function(form) {
        $('#forgot_password_submit').attr('disabled','disabled');
        $('#forgot_password_submit').html('Loading...');
        $('#forgotForm').ajaxSubmit({
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
                      $("#forgotModal").modal('hide');
                      $("#success_message").fadeOut(3000);
                      $("#forgotForm")[0].reset();
                  }, 1000);
                }
                $('#forgot_password_submit').removeAttr('disabled');
                $('#forgot_password_submit').html('Submit');
            }
        });
        return false;
      }
    });

  });
</script>
@endpush
