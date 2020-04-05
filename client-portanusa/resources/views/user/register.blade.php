<div class="modal fade modal-sign" id="modalRegister" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="header-modal mt-4 mb-4">
                    <img src="<?= DisplayMenu::getLogo() ?>" />
                    <h4 class="mt-3 mb-0">Create New Account</h4>
                </div>
                <form action="/register/form" id="register-form" enctype="multipart/form-data" method="POST" role="form" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="form-modal">
                        @if (session('success_register'))
                            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            {{ session('success_register') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                        @endif
                        @if (session('failed_register'))
                            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                            {{ session('failed_register') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h5><i class="far fa-user"></i> Full Name</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="first_name" placeholder="First Name" value="{{ old('first_name') }}">
                                        @if ($errors->has('first_name'))
                                        <h6 class="text-error">{{ $errors->first('first_name') }}</h6>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="last_name" placeholder="Last name" value="{{ old('last_name') }}">
                                        @if ($errors->has('last_name'))
                                        <h6 class="text-error">{{ $errors->first('last_name') }}</h6>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h5><i class="fas fa-mobile-alt"></i> Phone Number</h5>
                                <input type="text" class="form-control" maxlength="12" onkeypress="return isNumberKey(event)" name="handphone" value="{{ old('handphone') }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <h5><i class="far fa-envelope"></i> Email</h5>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                @if ($errors->has('email'))
                                <h6 class="text-error">{{ $errors->first('email') }}</h6>
                                @endif
                            </div>
                            <div class="col-md-12 mb-3">
                                <h5><i class="fas fa-lock"></i> Password</h5>
                                <input type="password" class="form-control" id="password" name="password">
                                @if ($errors->has('Password'))
                                <h6 class="text-error">{{ $errors->first('Password') }}</h6>
                                @endif
                            </div>
                            <div class="col-md-12 mb-3">
                                <h5><i class="fas fa-lock"></i> Confirm Password</h5>
                                <input type="password" class="form-control" name="confirm_password">
                                @if ($errors->has('confirm_password'))
                                <h6 class="text-error">{{ $errors->first('confirm_password') }}</h6>
                                @endif
                            </div>
                            <div class="col-md-12 mb-3">
                                <h5><i class="far fa-user-circle"></i> Person As</h5>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="person_as" id="person_as_personal" value="Personal" {{ (old('person_as') == 'Personal' ? 'checked' : '')}} onchange="checkPersonAs()">
                                            <label class="form-check-label" for="person_as_personal">Personal</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="person_as" id="person_as_company" value="Company" {{ (old('person_as') == 'Company' ? 'checked' : '')}} onchange="checkPersonAs()">
                                            <label class="form-check-label" for="person_as_company">Company</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="company d-none">
                                <div class="col-md-12 mb-3">
                                    <h5><i class="far fa-building"></i> Company Name</h5>
                                    <input type="text" class="form-control" name="company_name" required value="{{ old('company_name') }}">
                                    @if ($errors->has('company_name'))
                                    <h6 class="text-error">{{ $errors->first('company_name') }}</h6>
                                    @endif
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="row align-item-center">
                                        <div class="col-6">
                                            <h5 style="cursor:pointer" onclick="$('#document-file').click()"><i class="fa fa-upload"></i> Upload NPWP</h5>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="mt-0" id="document-name">&nbsp;</h6>
                                        </div>
                                        <input type="file" id="document-file" style="opacity:0;width:0px" name="document_file" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                {!! captcha_img() !!}<br/><br/>
                                <input type="text" name="captcha" placeholder="Captcha" class="form-control" maxlength="5">
                                @if ($errors->has('captcha'))
                                <h6 class="text-error">{{ $errors->first('captcha') }}</h6>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="button btn-primary w-100">Register</button>
                                <h6 class="text-center">Have an account ? <a href="javascript:;" onclick="showLoginModal()">Login Here</a></h6>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('plugin_scripts')
<script src="/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="/plugins/currency/currency.js"></script>
@endpush

@push('custom_scripts')
<script>
var _validFileExtensions = [".jpg", ".jpeg", ".png"];

function validateImage(oInput, textName) {
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
                $(textName).html("");
                return false;
            }else{
                $(textName).html(oInput.value);
            }
        }
    }
    return true;
}

function checkPersonAs(){
    if($('#person_as_company').is(':checked')){
        $('.company').removeClass('d-none');
        $('.company').addClass('d-block');
    }else{
        $('.company').addClass('d-none');
        $('.company').removeClass('d-block');
    }
}

function showLoginModal(){
    $('#modalRegister').modal('hide');
    setTimeout(function(){
        $('#modalSignIn').modal('show');
    }, 500)
}

$(function(){

    $('#document-file').change(function(){
        validateImage(this, "#document-name");
    });

    $('#ktp-file').change(function(){
        validateImage(this, "#ktp-name");
    });

    jQuery.validator.addMethod("checkPassword", function(value, element){
        if (!/[A-Z]/.test(value)) {
            return false;
        } else if (!/[a-z]/.test(value)) {
            return false;
        } else if (!/[0-9]/.test(value)) {
            return false;
        } else if (/^[a-zA-Z0-9- ]*$/.test(value)){
            return false;
        }

        return true;
    }, "Error");

    $( "#register-form" ).validate( {
        rules: {
            first_name: "required",
            last_name: "required",
            handphone: "required",
            // identity_no: "required",
            person_as: "required",
            captcha: "required",
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 8,
                checkPassword: true
            },
            confirm_password: {
                required: true,
                equalTo: "#password"
            },
        },
        messages: {
            first_name: "Please enter your first name",
            last_name: "Please enter your last name",
            handphone: "Please enter your phone number",
            identity_no: "Please enter your ktp number",
            company_name: "Please enter your company name",
            document_file: "Please enter your NPWP",
            ktp_file: "Please enter your KTP",
            person_as: "Please enter your person as",
            captcha: "Please enter captcha",
            email: {
                required: "Please enter your email",
                email: "Please enter a valid email address",
            },
            password: {
                required: "Please enter your password",
                min: "Please enter at least 8 characters",
                checkPassword: "Password must contain capital letters, numbers, and special characters",
            },
            confirm_password: {
                required: "Please enter your confirm password",
                equalTo: "Confirm password and password must match"
            }
        },
        errorElement: "h6",
        errorClass: "text-error",
        errorPlacement: function(error, element) {
            if (element.attr("name") == "person_as" ) {
                error.insertAfter(element.parent().parent().parent());
            } else {
                error.insertAfter(element);
            }

        }
    });



});
</script>
@if (session('success_register') || session('failed_register'))
<script>
    $(function(){
        showRegisterModal()
    })
</script>
@endif
@endpush

