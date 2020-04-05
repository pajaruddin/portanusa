<?php

namespace App\Http\Controllers;

use Validator;

use App\Header;

use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class SettingController extends Controller {

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $setting = Header::find(1);

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $data['logo'] = $domain . '/' . $path . '/' . $setting->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $setting->icon;

        $data['setting'] = $setting;

        return view('setting.page')->with($data);
    }

    public function update(Request $request) {
        $menu_uri = request()->segment(1);

        $attributeNames = array(
            'logo' => 'Logo',
            'icon' => 'Icon',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address'
        );

        $setting = Header::find(1);

        $validator = Validator::make($request->all(), array(
            'logo' => 'mimes:jpg,jpeg,png',
            'icon' => 'mimes:jpg,jpeg,png,ico',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $email = $request->input('email');
            $phone = $request->input('phone');
            $address = $request->input('address');

            $title = "portanusa";
            $slug = strtolower(str_slug($title, "_"));

            $logo_image = $setting->logo;
            $icon_image = $setting->icon;
            
            $asset_request = new AssetRequest;

            if ($request->hasFile('logo')) {
                $logo_image_file = $request->file('logo');
                $logo_filename = "logo_" . $slug . "_" . uniqid();
                $logo_full_filename = "logo_" . $slug . "_" . uniqid() . "." . $logo_image_file->getClientOriginalExtension();
                $logo_filetype = $logo_image_file->getClientMimeType();
                $logo_filepath = $_FILES['logo']['tmp_name'];
                $destination_path_logo = AppConfiguration::logoPath()->value;

                $upload_logo_image = $asset_request->upload($logo_filepath, $logo_filetype, $logo_full_filename, $destination_path_logo, $logo_filename);
                if ($upload_logo_image['code'] != 200) {
                    return redirect($menu_uri)->with('alert-error', $upload_logo_image['description']);
                } else {
                    if ($setting->logo != NULL) {
                        $asset_request->delete($destination_path_logo, $setting->logo);
                    }
                    $logo_image = $upload_logo_image['result']['file_name'];
                }
            }

            if ($request->hasFile('icon')) {
                $icon_image_file = $request->file('icon');
                $icon_filename = "icon_" . $slug . "_" . uniqid();
                $icon_full_filename = "icon_" . $slug . "_" . uniqid() . "." . $icon_image_file->getClientOriginalExtension();
                $icon_filetype = $icon_image_file->getClientMimeType();
                $icon_filepath = $_FILES['icon']['tmp_name'];
                $destination_path_icon = AppConfiguration::logoPath()->value;

                $upload_icon_image = $asset_request->upload($icon_filepath, $icon_filetype, $icon_full_filename, $destination_path_icon, $icon_filename);
                if ($upload_icon_image['code'] != 200) {
                    return redirect($menu_uri)->with('alert-error', $upload_icon_image['description']);
                } else {
                    if ($setting->icon != NULL) {
                        $asset_request->delete($destination_path_icon, $setting->icon);
                    }
                    $icon_image = $upload_icon_image['result']['file_name'];
                }
            }

            
            $setting->logo = $logo_image;
            $setting->icon = $icon_image;
            $setting->email = $email;
            $setting->phone = $phone;
            $setting->address = $address;

            if ($setting->save()) {

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Logo dan Icon']));
            } else {
                return redirect($menu_uri)->with('alert-error', trans('messages.failed_save', ['menu' => 'Logo dan Icon']));
            }
        } else {
            return redirect($menu_uri)->withErrors($validator)->withInput();
        }
    }

}