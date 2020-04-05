@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
    <div class="container">
        <div class="account mt-5 mb-5">
            <div class="row">
                <div class="col-sm-6 offset-sm-3">
                    <div class="information">
                        <h3 class="font-weight-light text-center">Reset Password</h3>
                        @if (session('failed'))
                            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                            {{ session('failed') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                        @endif
                        <form action="/reset-password/form" id="resetPassword-form" method="POST" role="form">
                            {{ csrf_field() }}
                            <input type="hidden" name="forgotten_password_code" value="{{ $forgotten_password_code }}" />
                            <table class="table mt-3">
                                <tr>
                                    <td colspan="2"><input type="email" class="form-control " name="email" placeholder="Email address" /></td>
                                </tr>
                                <tr>
                                    <td><input type="password" class="form-control " name="password" id="passwordReset" placeholder="New Password" /></td>
                                    <td><input type="password" class="form-control " name="confirm_password" placeholder="Confirm Password" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <button type="submit" style="cursor:pointer" class="btn btn-primary">Submit</button>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin_scripts')
<script src="/plugins/jquery-validation/jquery.validate.min.js"></script>
@endpush

@push('custom_styles')
<link rel="stylesheet" href="/css/global.css">
<link rel="stylesheet" href="/css/user.css">
@endpush

@push('custom_scripts')
<script src="/js/global.js"></script>
<script>
$(function(){

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

    $( "#resetPassword-form" ).validate( {
        rules: {
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
                equalTo: "#passwordReset"
            },
        },
        messages: {
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
            error.insertAfter(element);
        }
    });

});
</script>
@endpush