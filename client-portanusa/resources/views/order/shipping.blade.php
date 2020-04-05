<?php 
$province = Shipping::get_province($address->province_id);
$city = Shipping::get_city($address->city_id,$address->province_id);
?>
<div class="content-cart shipping mb-4">
    <div class="row">
        <div class="col-sm-12">
            <h5><b>{{ $address->receiver_name }}</b> ({{ $address->label }})</h5>
            <h5>{{ $address->receiver_phone }}</h5>
            <h5>
                {{ $address->address }}
            </h5>
            <h5>
                {{ $province['name'].", ". $city['type']." ".$city['city_name']." ". $address->postal_code}}
            </h5>
        </div>
    </div>
</div>