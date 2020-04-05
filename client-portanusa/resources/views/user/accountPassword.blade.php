@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
    <div class="container">
        <div class="account mt-5 mb-5">
            <div class="row">
                <div class="col-sm-3">
                    @include('user.navigation')
                </div>
                <div class="col-sm-9">
                    <div class="information">
                        <h3 class="font-weight-light mt-3">Form Change Password</h3>
                        <a href="/account" class="btn btn-sm btn-secondary"><i class="far fa-user mr-2"></i> My Account</a>
                        <a href="/account/edit" class="btn btn-sm btn-secondary"><i class="fa fa-pencil-alt mr-2"></i> Edit Account</a>
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show text-center mt-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                        @endif
                        @if (session('failed'))
                            <div class="alert alert-danger alert-dismissible fade show text-center mt-3" role="alert">
                            {{ session('failed') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                        @endif
                        <form action="/account/password/form" id="register-form" method="POST" role="form">
                            {{ csrf_field() }}
                            <table class="table mt-3">
                                <tr>
                                    <td colspan="2"><input type="password" class="form-control " name="password" placeholder="Old Password" /></td>
                                </tr>
                                <tr>
                                    <td><input type="password" class="form-control " name="new_password" id="new_password" placeholder="New Password" /></td>
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

    $( "#register-form" ).validate( {
        rules: {
            password: "required",
            new_password: {
                required: true,
                minlength: 8,
                checkPassword: true
            },
            confirm_password: {
                required: true,
                equalTo: "#new_password"
            },
        },
        messages: {
            password: "Please enter your old password",
            new_password: {
                required: "Please enter your password",
                min: "Please enter at least 8 characters",
                checkPassword: "Password must contain capital letters, numbers, and special characters",
            },
            confirm_password: {
                required: "Please enter your confirm password",
                equalTo: "Confirm password and new password must match"
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