<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Video;

class VideosController extends Controller
{
    function index(){
        $data['videos'] = Video::where('publish', 'T')->orderBy('id', 'desc')->paginate(16);
        $data['title'] = "Portanusa - Video";
        return view('video.page')->with($data);
    }
}
