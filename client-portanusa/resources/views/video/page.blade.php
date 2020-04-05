@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
<div class="footer-content mb-5">
    <div class="banner position-relative">
        <img src="/images/banner_background.jpg" class="img-fluid" />
        <div class="overlay position-absolute h-100 w-100 d-flex justify-content-center align-items-center">
            <h1>Videos</h1>
        </div>
    </div>
    <div class="container">
        <div class="videos">
            @if(count($videos) != 0)
            <div class="row">
                @foreach($videos as $video)
                <div class="col-md-6 col-lg-3">
                    <div class="content mt-4">
                        <a href="javascript:;" class="play-video" data-title="{{ $video->title }}" data-url="{{ $video->embed_url }}" data-desc="{{ $video->description }}">
                            <div class="image position-relative">
                                <img src="http://img.youtube.com/vi/{{ str_replace('https://www.youtube.com/embed/', '', $video->embed_url) }}/0.jpg" class="img-fluid" />
                                <span>Video</span>
                            </div>
                            <h5 class="pt-3 pb-3">{{ $video->title }}</h5>
                        </a>
                    </div>
                </div>
                @endforeach
                <div class="col-sm-12">
                    <div class="mt-3">
                        {{ $videos->links() }}
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-secondary text-center mt-4" role="alert">
                There is no data
            </div>
            @endif
        </div>
    </div>
</div>
@include('video.modal')
@endsection

@push('custom_styles')
<link rel="stylesheet" href="/css/global.css">
<link rel="stylesheet" href="/css/footerContent.css">
@endpush

@push('custom_scripts')
<script src="/js/global.js"></script>
<script>
    $(function(){
        initVideoModal();
    });
    
    function initVideoModal() {
        var items = document.getElementsByClassName('play-video');
        for (var x = 0; x < items.length; x++) {
            var item = items[x];
            item.addEventListener('click', function(fn) {
                var title  = $(this).attr("data-title");
                var url  = $(this).attr("data-url");
                var desc  = $(this).attr("data-desc");
                $('#videoModalLabel').html(title);
                $('#videoContent').attr("src", url);
                $('#descContent').html(desc);
                $('#videoModal').modal('show');
            });
        }
    }
</script>
@endpush