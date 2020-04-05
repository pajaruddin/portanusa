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
                        <a href="/account/edit" class="btn btn-sm btn-secondary mt-3"><i class="fa fa-pencil-alt mr-2"></i> Edit Account</a>
                        <table class="table mt-3">
                            <tr>
                                <td><h4 class="font-weight-normal">{{ $user->first_name.' '.$user->last_name }}</h4></td>
                            </tr>
                            {{-- <tr>
                                <td><h5 class="font-weight-light"><i class="far fa-id-card mr-2"></i> {{ $user->identity_no }}</h5></td>
                            </tr> --}}
                            <tr>
                                <td><h5 class="font-weight-light"><i class="far fa-envelope mr-2"></i> {{ $user->email }}</h5></td>
                            </tr>
                            <tr>
                                <td><h5 class="font-weight-light"><i class="fa fa-mobile-alt mr-2"></i> {{ $user->handphone }}</h5></td>
                            </tr>
                            <tr>
                                <td><h5 class="font-weight-light"><i class="far fa-user mr-2"></i> {{ $user->person_as }}</h5></td>
                            </tr>
                            @if($user->person_as == "Company")
                            <tr>
                                <td><h4 class="font-weight-light"><i class="far fa-building mr-2"></i> {{ $user->company_name }}</h4></td>
                            </tr>
                            @endif
                        </table>
                        <div class="row">
                            {{-- <div class="col-sm-4">
                                <div class="image mb-2">
                                    @if(!empty($user->ktp_file))
                                    <img src="{{ $asset_domain.'/'.$customerKtpImagePath.'/'.$user->ktp_file }}" class="img-thumbnail" />
                                    @else
                                    <img src="/images/picture-na.jpg" class="img-thumbnail w-100" />
                                    @endif
                                </div>
                            </div> --}}
                            <div class="col-sm-4">
                                <div class="image">
                                    @if(!empty($user->document_file))
                                    <img src="{{ $asset_domain.'/'.$customerNpwpImagePath.'/'.$user->document_file }}" class="img-thumbnail" />
                                    @else
                                    <img src="/images/picture-na.jpg" class="img-thumbnail w-100" />
                                    @endif
                                </div>
                            </div>
                        </div>
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

</script>
@endpush