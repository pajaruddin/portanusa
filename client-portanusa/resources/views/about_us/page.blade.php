@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
<div class="footer-content mb-5">
    <div class="banner position-relative">
        <img src="/images/banner_2_background.jpg" class="img-fluid" />
        <div class="overlay position-absolute h-100 w-100 d-flex justify-content-center align-items-center">
            <h1>About Us</h1>
        </div>
    </div>
    <div class="container">
        <div class="about-us-content mt-4 mb-4">
            <h3 class="font-weight-normal text-center">Who We Are</h3>
            <h6 class="font-weight-light text-center">
                Perform your duties with pleasure and use your humor at work, especially when it's difficult and tense, it's one of our cultures (pleasure).Religious, Passionate, Tough, Knowledgeful, Fun & Customer Service is a culture that exists at Bhinneka.Com, and we highly uphold our culture by providing the best for customers, ourselves, family and society..
            </h6>
            <div class="row mt-5">
                <div class="col-sm-4">
                    <img src="/images/blog_1.jpg" class="img-thumbnail" />
                    <h6 class="font-weight-light mt-4">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed pharetra eget augue a tincidunt. Donec sed interdum est. Quisque vitae convallis quam, id iaculis lectus. Nullam scelerisque odio at velit feugiat, nec vestibulum nibh auctor. In ullamcorper in lacus at facilisis. Praesent gravida nulla sit amet sagittis laoreet. Nunc finibus egestas lacus eu finibus.
                    </h6>
                </div>
                <div class="col-sm-4">
                    <img src="/images/blog_2.jpg" class="img-thumbnail" />
                    <h6 class="font-weight-light mt-4">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed pharetra eget augue a tincidunt. Donec sed interdum est. Quisque vitae convallis quam, id iaculis lectus. Nullam scelerisque odio at velit feugiat, nec vestibulum nibh auctor. In ullamcorper in lacus at facilisis. Praesent gravida nulla sit amet sagittis laoreet. Nunc finibus egestas lacus eu finibus.
                    </h6>
                </div>
                <div class="col-sm-4">
                    <img src="/images/blog_3.jpg" class="img-thumbnail" />
                    <h6 class="font-weight-light mt-4">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed pharetra eget augue a tincidunt. Donec sed interdum est. Quisque vitae convallis quam, id iaculis lectus. Nullam scelerisque odio at velit feugiat, nec vestibulum nibh auctor. In ullamcorper in lacus at facilisis. Praesent gravida nulla sit amet sagittis laoreet. Nunc finibus egestas lacus eu finibus.
                    </h6>
                </div>
            </div>
            @if(count($career_posts) != 0)
            <div class="viewed mt-4">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="viewed_title_container">
                                <h3 class="viewed_title text-center">Join With Us</h3>
                            </div>

                            <div class="viewed_slider_container">
                                
                                <!-- Recently Viewed Slider -->

                                <div class="owl-carousel owl-theme viewed_slider">
                                    <div class="row">
                                        @foreach($career_posts as $post)
                                        <div class="col-sm-12">
                                            <div class="content-career mb-3">
                                                <h3 class="mb-0" onclick="openCareer('#career-{{ $post->id }}')">{{ $post->position }}</h3>
                                                <div class="body mt-3" id="career-{{ $post->id }}">
                                                    <h6 class="mb-2">
                                                        Description
                                                    </h6>
                                                    {!! $post->job_description !!}
                                                    <h6 class="mb-2 mt-4">
                                                        Requirement
                                                    </h6>
                                                    {!! $post->job_requirement !!}
                                                </div>
                                            </div> 
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-12 text-center mt-4">
                            <a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#careerModal">Join One Position</a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@include('about_us.careerPopup')
@endsection

@push('custom_styles')
<link rel="stylesheet" href="/css/global.css">
<link rel="stylesheet" href="/css/product_detail.css">
<link rel="stylesheet" href="/css/footerContent.css">
@endpush

@push('custom_scripts')
<script src="/js/global.js"></script>
<script>
    $(function(){
        $('.content-career .body').hide();
    })

    function openCareer(career){
        $('.content-career .body').slideUp();
        $(career).slideDown();
    }
</script>
@endpush