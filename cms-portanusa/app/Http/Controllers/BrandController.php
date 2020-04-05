<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Brand;
use App\Header;

use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class BrandController extends Controller {

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;
        
        return view('brand.list')->with($data);
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $brands = Brand::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'name', 'description', 'publish')
            ->orderBy('id', 'desc')    
            ->get();

        return Datatables::of($brands)
                ->editColumn('active', function ($brand) {
                    if ($brand->publish == 'T') {
                        $active_checked = 'checked';
                    } else {
                        $active_checked = '';
                    }
                    $active = '<input type="checkbox" name="active" class="make-switch" data-id="' . $brand->id . '" data-size="mini" ' . $active_checked . '>';
                    
                    return $active;
                })
                ->addColumn('action', function ($brand) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/edit-brand", $brand->id) . '" class="btn btn-circle btn-icon-only btn-warning" title="Edit Brand"><i class="fa fa-pencil"></i></a>';
                    $action_button .= '<a href="#" id="delete" data-id="' . $brand->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Brand"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['active', 'action'])
                ->make(true);
    }

    function updateActive(Request $request) {
        if ($request->ajax()) {
            $brand = Brand::find($request->id);
            $active = $request->input('active');
            
            $brand->publish = $active;
            
            if ($brand->save()) {

                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_save', ['menu' => 'Brand'])
                ));
            }
        }
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $brand = Brand::find($request->id);

            if ($brand->delete()) {
                $asset_request = new AssetRequest;
                if ($brand->banner_image != NULL) {
                    $destination_path = AppConfiguration::brandBannerImagePath()->value;
                    $asset_request->delete($destination_path, $brand->banner_image);
                }

                $request->session()->flash('alert-success', trans('messages.success_delete', ['menu' => 'Brand']));
                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_delete', ['menu' => 'Brand'])
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
        return view('brand.create')->with($data);
    }

    public function create(Request $request) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'name' => 'Nama Brand',
            'banner_image' => 'Gambar Banner Brand'
        );

        $validator = Validator::make($request->all(), array(
                    'name' => 'required|unique:brands',
                    'banner_image' => 'required|mimes:jpg,jpeg,png'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $name = $request->input('name');
            $description_indonesia = $request->input('description_indonesia');
            $meta_description = $request->input('meta_description');
            $meta_keyword = $request->input('meta_keyword');
            $publish = $request->input('publish');

            $slug = strtolower(str_slug($name, "_"));
            $banner_image = NULL;
            $asset_request = new AssetRequest;

            if ($request->hasFile('banner_image')) {
                $banner_image_file = $request->file('banner_image');
                $banner_filename = "banner_" . $slug . "_" . uniqid();
                $banner_full_filename = "banner_" . $slug . "_" . uniqid() . "." . $banner_image_file->getClientOriginalExtension();
                $banner_filetype = $banner_image_file->getClientMimeType();
                $banner_filepath = $_FILES['banner_image']['tmp_name'];
                $destination_path = AppConfiguration::brandBannerImagePath()->value;

                $upload_banner_image = $asset_request->upload($banner_filepath, $banner_filetype, $banner_full_filename, $destination_path, $banner_filename);
                if ($upload_banner_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', $upload_banner_image['description']);
                } else {
                    $banner_image = $upload_banner_image['result']['file_name'];
                }
            }

            $brand = new Brand();
            $brand->name = $name;
            $brand->banner_image = $banner_image;
            $brand->description = ($description_indonesia != "") ? $description_indonesia : NULL;
            $brand->meta_description = ($meta_description != "") ? $meta_description : NULL;
            $brand->meta_keyword = ($meta_keyword != "") ? $meta_keyword : NULL;
            $brand->publish = ($publish != "") ? "T" : "F";
            $brand->url = $slug;
            if ($brand->save()) {
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

        $brand = Brand::find($id);

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        $data['brand'] = $brand;
        return view('brand.update')->with($data);
    }

    public function update(Request $request, $id) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'name' => 'Nama Brand',
            'banner_image' => 'Gambar Banner Brand',
            'url' => 'URL Brand'
        );

        $brand = Brand::find($id);

        if ($brand->name == $request->name) {
            $is_unique = "";
        } else {
            $is_unique = "|unique:brands";
        }

        if ($brand->url == $request->url) {
            $is_url_unique = "";
        } else {
            $is_url_unique = "|unique:categories";
        }

        $validator = Validator::make($request->all(), array(
                    'name' => 'required' . $is_unique,
                    'banner_image' => 'mimes:jpeg,png',
                    'url' => 'required' . $is_url_unique
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $name = $request->input('name');
            $description_indonesia = $request->input('description_indonesia');
            $meta_description = $request->input('meta_description');
            $meta_keyword = $request->input('meta_keyword');
            $publish = $request->input('publish');

            $slug = strtolower(str_slug($name, "_"));
            $banner_image = $brand->banner_image;
            $asset_request = new AssetRequest;


            if ($request->hasFile('banner_image')) {
                $banner_image_file = $request->file('banner_image');
                $banner_filename = "banner_" . $slug . "_" . uniqid();
                $banner_full_filename = "banner_" . $slug . "_" . uniqid() . "." . $banner_image_file->getClientOriginalExtension();
                $banner_filetype = $banner_image_file->getClientMimeType();
                $banner_filepath = $_FILES['banner_image']['tmp_name'];
                $destination_path = AppConfiguration::brandBannerImagePath()->value;

                $upload_banner_image = $asset_request->upload($banner_filepath, $banner_filetype, $banner_full_filename, $destination_path, $banner_filename);
                if ($upload_banner_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', $upload_banner_image['description']);
                } else {
                    if ($brand->banner_image != NULL) {
                        $asset_request->delete($destination_path, $brand->banner_image);
                    }
                    $banner_image = $upload_banner_image['result']['file_name'];
                }
            }

            $brand->name = $name;
            $brand->banner_image = $banner_image;
            $brand->description = ($description_indonesia != "") ? $description_indonesia : NULL;
            $brand->meta_description = ($meta_description != "") ? $meta_description : NULL;
            $brand->meta_keyword = ($meta_keyword != "") ? $meta_keyword : NULL;
            $brand->publish = ($publish != "") ? "T" : "F";
            $brand->url = $slug;
            if ($brand->save()) {

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Brand']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', trans('messages.failed_save', ['menu' => 'Brand']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->withErrors($validator)->withInput();
        }
    }

}