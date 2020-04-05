<div class="modal fade modal-sign" id="modalSignIn" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="header-modal mt-4 mb-4">
                    <img src="<?= DisplayMenu::getLogo() ?>" />
                    <h4 class="mt-3 mb-0">Sign In To Your Account</h4>
                </div>
                <form action="/sign-in/form" id="login-form" method="POST" role="form">
                    {{ csrf_field() }}
                    <div class="form-modal">
                        @if (session('success_login'))
                            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            {{ session('success_login') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                        @endif
                        @if (session('failed_login'))
                            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                            {{ session('failed_login') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <a href="/facebook/redirect" class="btn btn-outline-primary mb-3 w-100"><i class="fab fa-facebook-f"></i> Login with Facebook</a>
                            </div>
                            <div class="col-12">
                                <a href="/google/redirect" class="btn btn-outline-danger mb-3 w-100"><i class="fab fa-google"></i> Login with Google</a>
                            </div>
                        </div>
                        <h4 class="text-center mt-4 mb-4 new-customer-title">
                            <label>Or</label>
                        </h4>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h5><i class="far fa-envelope"></i> Email</h5>
                                <input type="email" class="form-control" name="email">
                            </div>
                            <div class="col-md-12 mb-3">
                                <h5><i class="fas fa-lock"></i> Password</h5>
                                <input type="password" class="form-control" name="password">
                                <h6 class="text-right"><a href="javascript:;" data-toggle="modal" data-target="#forgotModal">forgot your password ?</a></h6>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="button btn-primary w-100">Sign In</button>
                                <h6 class="text-center">Don't have an account ? <a href="javascript:;" onclick="showRegisterModal()">Register Here</a></h6>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('user.forgotPassword')

@push('custom_scripts')
<script>
$(function(){

    $( "#login-form" ).validate( {
        rules: {
            email: {
                required: true,
                email: true
            },
            password: "required"
        },
        messages: {
            email: {
                required: "Please enter your email",
                email: "Please enter a valid email address",
            },
            password: "Please enter your password"
        },
        errorElement: "h6",
        errorClass: "text-error",
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
    });

});

function showRegisterModal(){
    $('#modalSignIn').modal('hide');
    setTimeout(function(){
        $('#modalRegister').modal('show');
    }, 500)
}
</script>
@if (session('success_login') || session('failed_login'))
<script>
    $(function(){
        showLoginModal()
    })
</script>
@endif
@endpush

@push('plugin_scripts')
<script src="/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="/plugins/jquery-form/jquery.form.min.js"></script>
@endpush