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
                        <a href="/account/shipping/create" class="btn btn-sm btn-primary mt-3 mb-3"><i class="fa fa-plus mr-2"></i> Create New</a>
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
                        @if(count($shipping_address) != 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Receiver</th>
                                        <th>Shipping address</th>
                                        <th>Shipping area</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shipping_address as $address)
                                    <?php 
                                    $province = Shipping::get_province($address->province_id);
                                    $city = Shipping::get_city($address->city_id,$address->province_id);
                                    ?>
                                    <tr>
                                        <td><b>{{ $address->receiver_name }}</b><br/>{{ $address->receiver_phone }}</td>
                                        <td style="width:300px"><b>{{ $address->label }}</b><br/>{{ $address->address }}</td>
                                        <td>{{ $province['name'].", ". $city['type']." ".$city['city_name']." ". $address->postal_code}}</td>
                                        <td class="text-center">
                                            <a href="/account/shipping/edit/{{ $address->id }}" class="btn btn-outline-secondary btn-sm mt-1 mb-1"><i class="fa fa-pencil-alt"></i> Edit</a>
                                            <a href="javascript:;" data-shippingId="{{ $address->id }}" class="btn btn-outline-secondary btn-sm mt-1 mb-1 delete-shipping"><i class="fa fa-trash"></i> Delete</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-secondary" role="alert">
                            You have not made a shipping address
                        </div>
                        @endif
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

@push('custom_scripts')
<script src="/js/global.js"></script>
<script>
$(function(){
    initDelete();
});
</script>
<script>
function initDelete() {
    var items = document.getElementsByClassName('delete-shipping');
    for (var x = 0; x < items.length; x++) {
        var item = items[x];
        item.addEventListener('click', function(fn) {
            var shipping_id  = $(this).attr("data-shippingId");
            var n = new Noty({
                type: 'alert',
                text: 'Delete this address ?',
                layout: 'center',
                timeout: 2000,
                modal: true,
                buttons: [
                    Noty.button('Delete', 'btn btn-danger btn-sm', function () {
                        
                        $.ajax({
                            url: "{{url('/account/shipping/delete')}}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {shipping_id: shipping_id},
                            type: 'POST',
                            dataType: 'json',
                            success: function (result) {
                                new Noty({
                                    type: result.status,
                                    text: result.message,
                                    layout: 'center',
                                    timeout: 2000,
                                    modal: true
                                }).show();

                                setTimeout(function () {
                                    location.reload();
                                }, 2000);
                            }
                        });

                    }),

                    Noty.button('Cancel', 'ml-1 btn btn-secondary btn-sm', function () {
                        n.close();
                    })
                ]
            });
            n.show();

        });
    }
}
</script>
@endpush