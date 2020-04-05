<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Bank;
use App\Header;

use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class BankController extends Controller {

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        return view('bank.list')->with($data);
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $banks = Bank::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'name', 'no_rek', 'name_of')
                ->orderBy('id', 'desc')
                ->get();

        return Datatables::of($banks)
                ->addColumn('action', function ($bank) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/edit-bank", $bank->id) . '" class="btn btn-circle btn-icon-only btn-warning" title="Edit Bank"><i class="fa fa-pencil"></i></a>';
                    $action_button .= '<a href="#" id="delete" data-id="' . $bank->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Bank"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $bank = Bank::find($request->id);

            if ($bank->delete()) {
                $asset_request = new AssetRequest;
                if ($bank->logo != NULL) {
                    $destination_path = AppConfiguration::bankLogoPath()->value;
                    $asset_request->delete($destination_path, $bank->logo);
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
        return view('bank.create')->with($data);
    }

    public function create(Request $request) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'name' => 'Nama Bank',
            'no_rek' => 'No Rekening',
            'name_of' => 'Atas Nama',
            'logo' => 'Logo Bank'
        );

        $validator = Validator::make($request->all(), array(
            'name' => 'required|unique:banks',
            'no_rek' => 'required',
            'name_of' => 'required',
            'logo' => 'required|mimes:jpg,jpeg,png'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $name = $request->input('name');
            $no_rek = $request->input('no_rek');
            $name_of = $request->input('name_of');

            $slug = strtolower(str_slug($name, "_"));
            $logo = NULL;
            $asset_request = new AssetRequest;

            if ($request->hasFile('logo')) {
                $banner_image_file = $request->file('logo');
                $banner_filename = "logo_" . $slug . "_" . uniqid();
                $banner_full_filename = "logo_" . $slug . "_" . uniqid() . "." . $banner_image_file->getClientOriginalExtension();
                $banner_filetype = $banner_image_file->getClientMimeType();
                $banner_filepath = $_FILES['logo']['tmp_name'];
                $destination_path = AppConfiguration::bankLogoPath()->value;

                $upload_banner_image = $asset_request->upload($banner_filepath, $banner_filetype, $banner_full_filename, $destination_path, $banner_filename);
                if ($upload_banner_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', $upload_banner_image['description']);
                } else {
                    $logo = $upload_banner_image['result']['file_name'];
                }
            }

            $bank = new Bank();
            $bank->name = $name;
            $bank->logo = $logo;
            $bank->no_rek = $no_rek;
            $bank->name_of = $name_of;
            if ($bank->save()) {
                $menu = $menu_uri;

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Bank']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', trans('messages.failed_save', ['menu' => 'Bank']));
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

        $bank = Bank::find($id);

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        $data['bank'] = $bank;
        return view('bank.update')->with($data);
    }

    public function update(Request $request, $id) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'name' => 'Nama Bank',
            'no_rek' => 'No Rekening',
            'name_of' => 'Atas Nama',
            'logo' => 'Logo Bank'
        );

        $bank = Bank::find($id);

        if ($bank->name == $request->name) {
            $is_unique = "";
        } else {
            $is_unique = "|unique:banks";
        }

        $validator = Validator::make($request->all(), array(
            'name' => 'required' . $is_unique,
            'no_rek' => 'required',
            'name_of' => 'required',
            'logo' => 'mimes:jpg,jpeg,png'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $name = $request->input('name');
            $no_rek = $request->input('no_rek');
            $name_of = $request->input('name_of');

            $slug = strtolower(str_slug($name, "_"));
            $logo = $bank->logo;
            $asset_request = new AssetRequest;


            if ($request->hasFile('logo')) {
                $banner_image_file = $request->file('logo');
                $banner_filename = "logo_" . $slug . "_" . uniqid();
                $banner_full_filename = "logo_" . $slug . "_" . uniqid() . "." . $banner_image_file->getClientOriginalExtension();
                $banner_filetype = $banner_image_file->getClientMimeType();
                $banner_filepath = $_FILES['logo']['tmp_name'];
                $destination_path = AppConfiguration::bankLogoPath()->value;

                $upload_banner_image = $asset_request->upload($banner_filepath, $banner_filetype, $banner_full_filename, $destination_path, $banner_filename);
                if ($upload_banner_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', $upload_banner_image['description']);
                } else {
                    if ($bank->logo != NULL) {
                        $asset_request->delete($destination_path, $bank->logo);
                    }
                    $logo = $upload_banner_image['result']['file_name'];
                }
            }

            $bank->name = $name;
            $bank->logo = $logo;
            $bank->no_rek = $no_rek;
            $bank->name_of = $name_of;

            if ($bank->save()) {

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Bank']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', trans('messages.failed_save', ['menu' => 'Bank']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->withErrors($validator)->withInput();
        }
    }

}