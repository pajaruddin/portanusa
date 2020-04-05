<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Video;
use App\Header;

use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;
use App\Libraries\Youtube;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class VideosController extends Controller {

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        return view('video.list')->with($data);
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $videos = Video::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'title', 'publish')
                ->get();

        return Datatables::of($videos)
                ->editColumn('active', function ($video) {
                    if ($video->publish == 'T') {
                        $active_checked = 'checked';
                    } else {
                        $active_checked = '';
                    }
                    $active = '<input type="checkbox" name="active" class="make-switch" data-id="' . $video->id . '" data-size="mini" ' . $active_checked . '>';
                    
                    return $active;
                })
                ->addColumn('action', function ($video) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/edit-video", $video->id) . '" class="btn btn-circle btn-icon-only btn-warning" title="Edit Video"><i class="fa fa-pencil"></i></a>';
                    $action_button .= '<a href="#" id="delete" data-id="' . $video->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Video"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['active', 'action'])
                ->make(true);
    }

    function updateActive(Request $request) {
        if ($request->ajax()) {
            $video = Video::find($request->id);
            $active = $request->input('active');
            
            $video->publish = $active;
            
            if ($video->save()) {

                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_save', ['menu' => 'Video'])
                ));
            }
        }
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $video = Video::find($request->id);

            if ($video->delete()) {

                $request->session()->flash('alert-success', trans('messages.success_delete', ['menu' => 'Video']));
                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_delete', ['menu' => 'Video'])
                ));
            }
        }
    }

    function add() {
        $menu_uri = request()->segment(1);
        $menu = $menu_uri;

        $submenu_uri = request()->segment(2);
        $submenu = $submenu_uri;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        return view('video.create')->with($data);
    }

    public function create(Request $request) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'title' => 'Judul',
            'embed_url' => 'Video Url'
        );

        $validator = Validator::make($request->all(), array(
                    'title' => 'required|unique:videos',
                    'embed_url' => 'required'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $title = $request->input('title');
            $description_indonesia = $request->input('description_indonesia');
            $publish = $request->input('publish');
            $embed_url = ($request->input('embed_url') != "") ? "https://www.youtube.com/embed/" . Youtube::getId($request->input('embed_url')) : "";

            $slug = strtolower(str_slug($title, "_"));

            $video = new Video();
            $video->title = $title;
            $video->embed_url = ($embed_url != "") ? $embed_url : NULL;
            $video->description = ($description_indonesia != "") ? $description_indonesia : NULL;
            $video->publish = ($publish != "") ? "T" : "F";
            
            if ($video->save()) {
                $menu = $menu_uri;

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Brand']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', trans('messages.failed_save', ['menu' => 'Brand']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri)->withErrors($validator)->withInput();
        }
    }

    function edit($id) {
        $menu_uri = request()->segment(1);
        $menu = $menu_uri;

        $submenu_uri = request()->segment(2);
        $submenu = $submenu_uri;

        $video = Video::find($id);

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        $data['video'] = $video;
        return view('video.update')->with($data);
    }

    public function update(Request $request, $id) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'title' => 'Judul',
            'embed_url' => 'Video Url'
        );

        $video = Video::find($id);

        if ($video->title == $request->title) {
            $is_unique = "";
        } else {
            $is_unique = "|unique:videos";
        }


        $validator = Validator::make($request->all(), array(
                    'title' => 'required' . $is_unique,
                    'embed_url' => 'required'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $title = $request->input('title');
            $description_indonesia = $request->input('description_indonesia');
            $publish = $request->input('publish');
            $embed_url = ($request->input('embed_url') != "") ? "https://www.youtube.com/embed/" . Youtube::getId($request->input('embed_url')) : "";

            $slug = strtolower(str_slug($title, "_"));

            $video->title = $title;
            $video->embed_url = ($embed_url != "") ? $embed_url : NULL;
            $video->description = ($description_indonesia != "") ? $description_indonesia : NULL;
            $video->publish = ($publish != "") ? "T" : "F";

            if ($video->save()) {

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Brand']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', trans('messages.failed_save', ['menu' => 'Brand']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->withErrors($validator)->withInput();
        }
    }

}