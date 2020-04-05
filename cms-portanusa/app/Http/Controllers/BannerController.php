<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Banner;
use App\Header;

use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class BannerController extends Controller {

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;
        
        return view('banner.list')->with($data);
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $Banners = Banner::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'title', 'subtitle', 'date_start', 'date_end', 'publish')
                ->orderBy('id', 'desc')
                ->get();

        return Datatables::of($Banners)
                ->editColumn('active', function ($banner) {
                    if ($banner->publish == 'T') {
                        $active_checked = 'checked';
                    } else {
                        $active_checked = '';
                    }
                    $active = '<input type="checkbox" name="active" class="make-switch" data-id="' . $banner->id . '" data-size="mini" ' . $active_checked . '>';
                    
                    return $active;
                })
                ->editColumn('date_start', function ($banner) {
                    return date('d M Y', strtotime($banner->date_start));
                })
                ->editColumn('date_end', function ($banner) {
                    return date('d M Y', strtotime($banner->date_end));
                })
                ->addColumn('action', function ($banner) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/edit-banner", $banner->id) . '" class="btn btn-circle btn-icon-only btn-warning" title="Edit Banner"><i class="fa fa-pencil"></i></a>';
                    $action_button .= '<a href="#" id="delete" data-id="' . $banner->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Banner"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['active', 'action'])
                ->make(true);
    }

    function updateActive(Request $request) {
        if ($request->ajax()) {
            $banner = Banner::find($request->id);
            $active = $request->input('active');
            
            $banner->publish = $active;
            
            if ($banner->save()) {

                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_save', ['menu' => 'Banner'])
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
        return view('banner.create')->with($data);
    }

    public function create(Request $request) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'title' => 'Title',
            'subtitle' => 'Subtitle',
            'url' => 'Url',
            'date_start' => 'Tanggal Awal',
            'date_end' => 'Tanggal Akhir',
            'banner_image' => 'Gambar Banner'
        );

        $validator = Validator::make($request->all(), array(
            'title' => 'required|unique:banners',
            'subtitle' => 'required',
            'url' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
            'banner_image' => 'required|mimes:jpg,jpeg,png'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $title = $request->input('title');
            $subtitle = $request->input('subtitle');
            $url = $request->input('url');
            $publish = $request->input('publish');
            $date_start = $request->input('date_start');
            $date_end = $request->input('date_end');

            $slug = strtolower(str_slug($title, "_"));
            $banner_image = NULL;
            $asset_request = new AssetRequest;

            if ($request->hasFile('banner_image')) {
                $banner_image_file = $request->file('banner_image');
                $banner_filename = "banner_" . $slug . "_" . uniqid();
                $banner_full_filename = "banner_" . $slug . "_" . uniqid() . "." . $banner_image_file->getClientOriginalExtension();
                $banner_filetype = $banner_image_file->getClientMimeType();
                $banner_filepath = $_FILES['banner_image']['tmp_name'];
                $destination_path = AppConfiguration::bannerPath()->value;

                $upload_banner_image = $asset_request->upload($banner_filepath, $banner_filetype, $banner_full_filename, $destination_path, $banner_filename);
                if ($upload_banner_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', $upload_banner_image['description']);
                } else {
                    $banner_image = $upload_banner_image['result']['file_name'];
                }
            }

            $model = new Banner();
            $model->subtitle = $subtitle;
            $model->title = $title;
            $model->image = $banner_image;
            $model->date_start = date('Y-m-d', strtotime($date_start));
            $model->date_end = date('Y-m-d', strtotime($date_end));
            $model->publish = ($publish != "") ? "T" : "F";
            $model->url = $url;
            if ($model->save()) {
                $menu = $menu_uri;

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Banner']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', trans('messages.failed_save', ['menu' => 'Banner']));
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

        $banner = Banner::find($id);
        if (!empty($banner)) {

            $domain = AppConfiguration::assetPortalDomain()->value;
            $path = AppConfiguration::logoPath()->value;

            $header = Header::find(1);
            $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
            $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

            $data['menu'] = $menu;
            $data['submenu'] = $submenu;
            $data['banner'] = $banner;

            return view('banner.update')->with($data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request, $id) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'title' => 'Title',
            'subtitle' => 'Subtitle',
            'url' => 'Url',
            'date_start' => 'Tanggal Awal',
            'date_end' => 'Tanggal Akhir',
            'banner_image' => 'Gambar Banner'
        );

        $banner = Banner::find($id);

        if ($banner->title == $request->title) {
            $is_unique = "";
        } else {
            $is_unique = "|unique:banners";
        }

        $validator = Validator::make($request->all(), array(
            'title' => 'required' . $is_unique,
            'subtitle' => 'required',
            'url' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
            'banner_image' => 'mimes:jpg,jpeg,png'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $title = $request->input('title');
            $subtitle = $request->input('subtitle');
            $url = $request->input('url');
            $publish = $request->input('publish');
            $date_start = $request->input('date_start');
            $date_end = $request->input('date_end');

            $slug = strtolower(str_slug($title, "_"));
            $banner_image = $banner->image;
            $asset_request = new AssetRequest;


            if ($request->hasFile('banner_image')) {
                $banner_image_file = $request->file('banner_image');
                $banner_filename = "banner_" . $slug . "_" . uniqid();
                $banner_full_filename = "banner_" . $slug . "_" . uniqid() . "." . $banner_image_file->getClientOriginalExtension();
                $banner_filetype = $banner_image_file->getClientMimeType();
                $banner_filepath = $_FILES['banner_image']['tmp_name'];
                $destination_path = AppConfiguration::bannerPath()->value;

                $upload_banner_image = $asset_request->upload($banner_filepath, $banner_filetype, $banner_full_filename, $destination_path, $banner_filename);
                if ($upload_banner_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', $upload_banner_image['description']);
                } else {
                    if ($banner->image != NULL) {
                        $asset_request->delete($destination_path, $banner->image);
                    }
                    $banner_image = $upload_banner_image['result']['file_name'];
                }
            }

            $banner->subtitle = $subtitle;
            $banner->title = $title;
            $banner->image = $banner_image;
            $banner->date_start = date('Y-m-d', strtotime($date_start));
            $banner->date_end = date('Y-m-d', strtotime($date_end));
            $banner->publish = ($publish != "") ? "T" : "F";
            $banner->url = $url;

            if ($banner->save()) {

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Banner']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', trans('messages.failed_save', ['menu' => 'Banner']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->withErrors($validator)->withInput();
        }
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $banner = Banner::find($request->id);

            if ($banner->delete()) {
                $asset_request = new AssetRequest;
                if ($banner->image != NULL) {
                    $destination_path = AppConfiguration::bannerPath()->value;
                    $asset_request->delete($destination_path, $banner->image);
                }

                $request->session()->flash('alert-success', trans('messages.success_delete', ['menu' => 'Banner']));
                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_delete', ['menu' => 'Banner'])
                ));
            }
        }
    }


}