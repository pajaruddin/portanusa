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
                        <a href="/account/shipping" class="btn btn-sm btn-secondary mt-3 mb-3"><i class="fa fa-chevron-left mr-2"></i> Cancel</a>
                        @if (session('failed'))
                            <div class="alert alert-danger alert-dismissible fade show text-center mt-3" role="alert">
                            {{ session('failed') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                        @endif
                        <form action="/account/shipping/update" id="shipping-form" method="POST" role="form">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $shipping->id }}" />
                            <table class="table mt-3">
                                <tr>
                                    <td><input type="text" class="form-control" value="{{ $shipping->label }}" name="label" placeholder="Shipping label ex: Kantor" /></td>
                                    <td><input type="text" class="form-control" value="{{ $shipping->receiver_name }}" name="receiver_name" placeholder="Receiver name" /></td>
                                    
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <textarea class="form-control " rows="3" name="address" placeholder="Address">{{ $shipping->address }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select id="province" name="province_id" class="form-control ml-0">
                                            <option value="">Province</option>
                                            @if(!empty($data_provinces))
                                                @foreach($data_provinces as $province)
                                                    <option value="{{ $province['province_id'] }}" {{ ($province['province_id'] == $shipping->province_id ? 'selected' : '') }}>{{ $province['province'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                    <td>
                                        <select id="city" name="city_id" class="form-control ml-0">
                                            <option value="">City</option>
                                            @if(!empty($data_cities))
                                                @foreach($data_cities as $city)
                                                    <option value="{{ $city['city_id'] }}" {{ ($city['city_id'] == $shipping->city_id ? 'selected' : '') }}>{{ $city['type'].' '.$city['city_name'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="text" class="form-control " value="{{ $shipping->postal_code }}" name="postal_code" onkeypress="return isNumberKey(event)" maxlength="5" placeholder="Postal Code" /></td>
                                    <td><input type="text" class="form-control " value="{{ $shipping->receiver_phone }}" name="receiver_phone" maxlength="12" onkeypress="return isNumberKey(event)" placeholder="Receiver phone" /></td>
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

@push('custom_styles')
<link rel="stylesheet" href="/css/global.css">
<link rel="stylesheet" href="/css/user.css">
@endpush

@push('plugin_scripts')
<script src="/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="/plugins/currency/currency.js"></script>
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
                    var city_option = "<option value=''>City</option>";
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

    $( "#shipping-form" ).validate( {
        rules: {
            label: "required",
            receiver_name: "required",
            receiver_phone: "required",
            address: "required",
            province_id: "required",
            city_id: "required",
            postal_code: "required",
        },
        messages: {
            label: "Please enter your shipping label",
            receiver_name: "Please enter your receiver name",
            receiver_phone: "Please enter your receiver phone",
            address: "Please enter your address",
            province_id: "Please enter your province",
            city_id: "Please enter your city",
            postal_code: "Please enter your postal code",
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