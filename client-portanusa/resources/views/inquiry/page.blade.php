@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
    <div class="sign-in">
        <div class="contact_form">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="contact_form_container">
                            <div class="contact_form_title text-center">Inquiry Form</div>
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                            @endif
                            @if (session('failed'))
                                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                                {{ session('failed') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                            @endif
                            <form action="/inquiry/form" id="inquiryForm" method="POST" role="form" autocomplete="off">
                                {{ csrf_field() }}
                                
                                <div class="contact_form_inputs d-flex flex-md-row flex-column justify-content">
                                    <input type="text" class="input_field" name="full_name" placeholder="Full name" value="{{ old('full_name') }}">
                                </div>
                                @if ($errors->has('full_name'))
                                <h6 class="text-error">{{ $errors->first('full_name') }}</h6>
                                @endif

                                <div class="contact_form_inputs flex-md-row flex-column justify-content">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="person_as" id="person_as_personal" value="Personal" {{ (old('person_as') == 'Personal' ? 'checked' : '')}} onchange="checkPersonAs()">
                                                <label class="form-check-label" for="person_as_personal">Personal</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="person_as" id="person_as_company" value="Company" {{ (old('person_as') == 'Company' ? 'checked' : '')}} onchange="checkPersonAs()">
                                                <label class="form-check-label" for="person_as_company">Company</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($errors->has('person_as'))
                                <h6 class="text-error">{{ $errors->first('person_as') }}</h6>
                                @endif
                                
                                <div class="contact_form_inputs d-flex flex-md-row flex-column justify-content">
                                    <input type="text" class="input_field" name="company_name" placeholder="Company name" value="{{ old('company_name') }}">
                                </div>
                                @if ($errors->has('company_name'))
                                <h6 class="text-error">{{ $errors->first('company_name') }}</h6>
                                @endif
                                
                                <div class="contact_form_inputs d-flex flex-md-row flex-column justify-content">
                                    <input type="text" class="input_field mr-1" maxlength="12" onkeypress="return isNumberKey(event)" name="handphone" placeholder="Phone Number" value="{{ old('handphone') }}">
                                    <input type="text" class="input_field" maxlength="12" onkeypress="return isNumberKey(event)" name="telephone" placeholder="Telephone" value="{{ old('telephone') }}">
                                </div>
                                
                                <div class="contact_form_inputs d-flex flex-md-row flex-column justify-content">
                                    <input type="email" class="input_field mr-1" placeholder="Email address" name="email" value="{{ old('email') }}" id="email">
                                    <input type="email" class="input_field" placeholder="Confirm Email" name="confirm_email" value="{{ old('confirm_email') }}">
                                </div>
                                @if ($errors->has('email'))
                                <h6 class="text-error">{{ $errors->first('email') }}</h6>
                                @endif
                                @if ($errors->has('confirm_email'))
                                <h6 class="text-error">{{ $errors->first('confirm_email') }}</h6>
                                @endif

                                <div class="contact_form_inputs d-flex flex-md-row flex-column justify-content">
                                    <select id="province" name="province_id" class="input_field mr-1 ml-0">
                                        <option value="">Province</option>
                                        @if(!empty($data_provinces))
                                            @foreach($data_provinces as $province)
                                                <option value="{{ $province['province_id'] }}">{{ $province['province'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <select id="city" name="state_id" class="input_field ml-0" disabled>
                                        <option value="">City</option>
                                    </select>
                                </div>

                                <div class="contact_form_inputs d-flex flex-md-row flex-column justify-content">
                                    <input type="text" class="input_field" maxlength="6" onkeypress="return isNumberKey(event)" name="postal_zip" placeholder="Postal Code" value="{{ old('postal_zip') }}">
                                </div>

                                <div class="contact_form_inputs d-flex flex-md-row flex-column justify-content">
                                    <textarea class="input_field pt-2 " name="address" placeholder="Address"></textarea>
                                </div>

                                <div class="contact_form_inputs d-flex flex-md-row flex-column justify-content">
                                        <textarea class="input_field pt-2 " name="message" placeholder="Message"></textarea>
                                    </div>
                                
                                <div class="contact_form_button">
                                    <button type="submit" class="button contact_submit_button btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel"></div>
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
                        var city_option = "<option value=''>Select City</option>";
                        for (i in cities) {
                            city_option += "<option value='" + cities[i].id + "'>" + cities[i].type + " " + cities[i].name + "</option>";
                        }
                        $(city_id).empty();
                        $(city_id).append(city_option);
                        $(city_id).removeAttr('disabled');
                    } else {
                        var city_option = "<option value=''></option>";
                        $(city_id).empty();
                        $(city_id).append(city_option);
                        $(city_id).attr('disabled');
                        alert(result.message);
                    }
                }
            });
        } else {
            var city_option = "<option value=''></option>";
            $(city_id).empty();
            $(city_id).append(city_option);
            $(city_id).attr('disabled');
        }
    }
</script>
<script>
    $(function(){
        $("#province").change(function () {
            var province_id = $(this).val();
            getCities(province_id, '#city');
        });

        $( "#inquiryForm" ).validate( {
            rules: {
                full_name: "required",
                person_as: "required",
                handphone: "required",
                email: {
                    required: true,
                    email: true
                },
                confirm_email: {
                    required: true,
                    email: true,
                    equalTo: "#email"
                },
                address: "required",
                province_id: "required",
                state_id: "required",
                postal_zip: "required",
                message: "required"
            },
            messages: {
                full_name: "Please enter your full name",
                person_as: "Please enter your inquiry for",
                handphone: "Please enter your phone number",
                email: {
                    required: "Please enter your email",
                    email: "Please enter a valid email address",
                },
                confirm_email: {
                    required: "Please enter your confirm email",
                    email: "Please enter a valid email address",
                    equalTo: "Confirm email and email must match"
                },
                address: "Please enter your address",
                province_id: "Please enter your province",
                state_id: "Please enter your city",
                postal_zip: "Please enter your postal code",
                message: "Please enter your message",
            },
            errorElement: "h6",
            errorClass: "text-error",
            errorPlacement: function(error, element) {
                if (element.attr("name") == "person_as" ) {
                    error.insertAfter(element.parent().parent().parent());
                } else {
                    error.insertAfter(element.parent());
                }

            }
        });

    });
</script>
@endpush

