<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Service;
use App\Header;

use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ServiceController extends Controller {

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        return view('service.list')->with($data);
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $services = Service::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'title', 'description')
                ->get();

        return Datatables::of($services)
                ->addColumn('action', function ($service) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/edit-service", $service->id) . '" class="btn btn-circle btn-icon-only btn-warning" title="Edit Service"><i class="fa fa-pencil"></i></a>';
                    $action_button .= '<a href="#" id="delete" data-id="' . $service->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Service"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $service = Service::find($request->id);

            if ($service->delete()) {
                $asset_request = new AssetRequest;
                if ($service->image != NULL) {
                    $destination_path = AppConfiguration::servicePath()->value;
                    $asset_request->delete($destination_path, $service->image);
                }

                $request->session()->flash('alert-success', trans('messages.success_delete', ['menu' => 'Service']));
                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_delete', ['menu' => 'Service'])
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
        return view('service.create')->with($data);
    }

    public function create(Request $request) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'title' => 'Nama Service',
            'description_service' => 'Description',
            'logo' => 'Gambar Service'
        );

        $validator = Validator::make($request->all(), array(
            'title' => 'required|unique:services',
            'description_service' => 'required',
            'logo' => 'required|mimes:jpg,jpeg,png'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $title = $request->input('title');
            $description_service = $request->input('description_service');

            $slug = strtolower(str_slug($title, "_"));
            $logo = NULL;
            $asset_request = new AssetRequest;

            if ($request->hasFile('logo')) {
                $banner_image_file = $request->file('logo');
                $banner_filename = "logo_" . $slug . "_" . uniqid();
                $banner_full_filename = "logo_" . $slug . "_" . uniqid() . "." . $banner_image_file->getClientOriginalExtension();
                $banner_filetype = $banner_image_file->getClientMimeType();
                $banner_filepath = $_FILES['logo']['tmp_name'];
                $destination_path = AppConfiguration::servicePath()->value;

                $upload_banner_image = $asset_request->upload($banner_filepath, $banner_filetype, $banner_full_filename, $destination_path, $banner_filename);
                if ($upload_banner_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', $upload_banner_image['description']);
                } else {
                    $logo = $upload_banner_image['result']['file_name'];
                }
            }

            $service = new Service();
            $service->title = $title;
            $service->description = $description_service;
            $service->image = $logo;
            if ($service->save()) {
                $menu = $menu_uri;

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Service']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', trans('messages.failed_save', ['menu' => 'Service']));
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

        $service = Service::find($id);

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        $data['service'] = $service;
        return view('service.update')->with($data);
    }

    public function update(Request $request, $id) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'title' => 'Nama Service',
            'description_service' => 'Description',
            'logo' => 'Gambar Service'
        );

        $service = Service::find($id);

        if ($service->title == $request->title) {
            $is_unique = "";
        } else {
            $is_unique = "|unique:services";
        }

        $validator = Validator::make($request->all(), array(
            'title' => 'required' . $is_unique,
            'description_service' => 'required',
            'logo' => 'mimes:jpg,jpeg,png'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $title = $request->input('title');
            $description_service = $request->input('description_service');

            $slug = strtolower(str_slug($title, "_"));
            $logo = $service->image;
            $asset_request = new AssetRequest;


            if ($request->hasFile('logo')) {
                $banner_image_file = $request->file('logo');
                $banner_filename = "logo_" . $slug . "_" . uniqid();
                $banner_full_filename = "logo_" . $slug . "_" . uniqid() . "." . $banner_image_file->getClientOriginalExtension();
                $banner_filetype = $banner_image_file->getClientMimeType();
                $banner_filepath = $_FILES['logo']['tmp_name'];
                $destination_path = AppConfiguration::servicePath()->value;

                $upload_banner_image = $asset_request->upload($banner_filepath, $banner_filetype, $banner_full_filename, $destination_path, $banner_filename);
                if ($upload_banner_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', $upload_banner_image['description']);
                } else {
                    if ($service->image != NULL) {
                        $asset_request->delete($destination_path, $service->image);
                    }
                    $logo = $upload_banner_image['result']['file_name'];
                }
            }

            $service->title = $title;
            $service->description = $description_service;
            $service->image = $logo;

            if ($service->save()) {

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Service']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', trans('messages.failed_save', ['menu' => 'Service']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->withErrors($validator)->withInput();
        }
    }

}