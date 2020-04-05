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
                        <h3 class="font-weight-light mt-3">Form Edit Account</h3>
                        <a href="/account" class="btn btn-sm btn-secondary"><i class="far fa-user mr-2"></i> My Account</a>
                        <a href="/account/password" class="btn btn-sm btn-secondary"><i class="fa fa-lock mr-2"></i> Change Password</a>
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
                        <form action="/account/edit/form" id="register-form" method="POST" role="form">
                            {{ csrf_field() }}
                            <table class="table mt-3">
                                <tr>
                                    <td><input type="text" class="form-control " name="first_name" value="{{ $user->first_name }}" placeholder="First Name" /></td>
                                    <td><input type="text" class="form-control " name="last_name" value="{{ $user->last_name }}" placeholder="Last Name" /></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="contact_form_container">
                                            <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input" type="radio" name="gender" id="gender_male" value="L" {{ ($user->gender == 'L' ? 'checked' : '')}}>
                                                <label class="form-check-label" for="gender_male">Male</label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="contact_form_container">
                                            <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input" type="radio" name="gender" id="gender_female" value="P" {{ ($user->gender == 'P' ? 'checked' : '')}}>
                                                <label class="form-check-label" for="gender_female">Female</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="pr-4 pl-0">
                                        <h5 class="font-weight-light mt-0 ml-2">Birthday</h5>
                                        <div class="d-flex">
                                            <div class="col-sm-4 pl-0">
                                                <select name="day" class="form-control ">
                                                    <option value="">Day</option>
                                                    <?php for($i = 1;$i<=31;$i++){ ?>
                                                        <option value="{{ $i }}" {{ (date('d', strtotime($user->birthday)) == $i && $user->birthday != "" ? 'selected' : '') }}>{{ $i }}</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <select name="month" class="form-control ">
                                                    <option value="">Month</option>
                                                    <?php 
                                                        for($i = 1;$i<=12;$i++){                                                     
                                                        $dateObj   = DateTime::createFromFormat('!m', $i);
                                                        $monthName = $dateObj->format('F'); 
                                                    ?>
                                                        <option value="{{ $i }}" {{ (date('m', strtotime($user->birthday)) == $i && $user->birthday != "" ? 'selected' : '') }}>{{ $monthName }}</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-4 pr-0">
                                                <select name="year" class="form-control ">
                                                    <option value="">Year</option>
                                                    <?php
                                                        $year = date('Y') - 17; 
                                                        for($i = 1938;$i<=$year;$i++){ 
                                                    ?>
                                                        <option value="{{ $i }}" {{ (date('Y', strtotime($user->birthday)) == $i  && $user->birthday != "" ? 'selected' : '') }}>{{ $i }}</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <textarea class="form-control " rows="3" name="address" placeholder="Address">{{ $user->address }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="pr-4 pl-0">
                                        <div class="d-flex">
                                            <div class="col-sm-6 pl-0">
                                                <select id="province" name="province_id" class="form-control ">
                                                    <option value="">Province</option>
                                                    @if(!empty($data_provinces))
                                                        @foreach($data_provinces as $province)
                                                            <option value="{{ $province['province_id'] }}" {{ ($province['province_id'] == $user->province_id ? 'selected' : '') }}>{{ $province['province'] }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-sm-6 pr-0">
                                                <select id="city" name="city_id" class="form-control " {{ (!empty($user->city_id) ? "" : "disabled") }}>
                                                    <option value="">City</option>
                                                    @if(!empty($data_cities))
                                                        @foreach($data_cities as $city)
                                                            <option value="{{ $city['city_id'] }}" {{ ($city['city_id'] == $user->city_id ? 'selected' : '') }}>{{ $city['type'].' '.$city['city_name'] }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="text" class="form-control " name="postal_code" onkeypress="return isNumberKey(event)" maxlength="5" value="{{ $user->postal_code }}" placeholder="Postal Code" /></td>
                                    <td><input type="text" class="form-control " name="handphone" onkeypress="return isNumberKey(event)" maxlength="12" value="{{ $user->handphone }}" placeholder="Handphone" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><input type="email" class="form-control " name="email" value="{{ $user->email }}" placeholder="Email" readonly /></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <input type="text" class="form-control " name="person_as" value="{{ $user->person_as }}" placeholder="Email" readonly />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <input type="text" class="form-control " name="company_name" value="{{ $user->company_name }}" placeholder="Company Name" />
                                    </td>
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
<script src="/plugins/currency/currency.js"></script>
@endpush

@push('custom_styles')
<link rel="stylesheet" href="/css/global.css">
<link rel="stylesheet" href="/css/user.css">
@endpush

@push('custom_scripts')
<script src="/js/global.js"></script>
<script>
function getCities(province_id, city_id){
    if (province_id != "") {
        $.ajax({
            url: "{{url('/shipping-address/get-cities')}}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {province_id: province_id},
            type: 'POST',
            dataType: 'json',
            success: function (result) {
                if (result.status == "success") {
                    var i;
                    var cities = result.data;
                    var city_option = "<option value=''></option>";
                    for (i in cities) {
                        city_option += "<option value='" + cities[i].id + "'>" + cities[i].type + " " + cities[i].name + "</option>";
                    }
                    $(city_id).empty();
                    $(city_id).append(city_option);
                    $(city_id).removeAttr('disabled');
                } else {
                    var city_option = "<option value=''>City</option>";
                    $(city_id).empty();
                    $(city_id).append(city_option);
                    $(city_id).attr('disabled', 'disabled');
                    alert(result.message);
                }
            }
        });
    } else {
        var city_option = "<option value=''>City</option>";
        $(city_id).empty();
        $(city_id).append(city_option);
        $(city_id).attr('disabled', 'disabled');
    }
}
</script>
<script>
$(function(){

    $("#province").change(function () {
        var province_id = $(this).val();
        getCities(province_id, '#city');
    });

    $( "#register-form" ).validate( {
        rules: {
            first_name: "required",
            last_name: "required",
            person_as: "required",
        },
        messages: {
            first_name: "Please enter your first name",
            last_name: "Please enter your last name",
            person_as: "Please enter your person as",
        },
        errorElement: "h6",
        errorClass: "text-error",
        errorPlacement: function(error, element) {
            if (element.attr("name") == "person_as") {
                error.insertAfter(element.parent().parent());
            } else {
                error.insertAfter(element);
            }

        }
    });

});
</script>
@endpush