<?php

namespace App\Http\Controllers;

use Validator;

use App\Header;

use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ProfileController extends Controller {

    function __construct() {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                return redirect('/login');
            }
            return $next($request);
        });
    }

    function index(Request $request) {
        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['tab'] = $request->session()->get('tab', 'personal');
        return view('profile.page', $data);
    }

    public function personal(Request $request) {
        $attributeNames = array(
            'first_name' => 'Nama Depan',
        );

        $validator = Validator::make($request->all(), array(
                    'first_name' => 'required'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $first_name = $request->input('first_name');
            $last_name = $request->input('last_name');
            $telephone = $request->input('telephone');
            $handphone = $request->input('handphone');

            $user = Auth::user();
            $user->first_name = $first_name;
            $user->last_name = ($last_name != "") ? $last_name : NULL;
            $user->telephone = ($telephone != "") ? $telephone : NULL;
            $user->handphone = ($handphone != "") ? $handphone : NULL;
            if ($user->save()) {
                return redirect('profile')->with('alert-success', trans('messages.success_save', ['menu' => 'Profil']));
            } else {
                return redirect('profile')->with('alert-error', trans('messages.failed_save', ['menu' => 'Profil']));
            }
        } else {
            return redirect('profile')->withErrors($validator)->withInput();
        }
    }

    public function avatar(Request $request) {
        $request->session()->flash('tab', 'avatar');

        $attributeNames = array(
            'photo_image' => 'Avatar'
        );

        $validator = Validator::make($request->all(), array(
                    'photo_image' => 'required|mimes:jpeg,png'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $user = Auth::user();
            $first_name = $user->first_name;            
            $photo_image = $user->photo_image;
            
            $slug = strtolower(str_slug($first_name, "_"));            
            $asset_request = new AssetRequest;            
            if ($request->hasFile('photo_image')) {
                $photo_image_file = $request->file('photo_image');
                $photo_filename = $slug . "_" . uniqid();
                $photo_full_filename = $photo_filename . "." . $photo_image_file->getClientOriginalExtension();
                $photo_filetype = $photo_image_file->getClientMimeType();
                $photo_filepath = $_FILES['photo_image']['tmp_name'];
                $destination_path = AppConfiguration::avatarImagePath()->value;

                $upload_photo_image = $asset_request->upload($photo_filepath, $photo_filetype, $photo_full_filename, $destination_path, $photo_filename);
                if ($upload_photo_image['code'] != 200) {
                    return redirect("profile")->with('alert-error', $upload_photo_image['description']);
                } else {
                    $destination_delete_path = AppConfiguration::avatarImagePath()->value;
                    if ($user->photo_image != NULL) {
                        $asset_request->delete($destination_delete_path, Auth::user()->photo_image);
                    }
                    $photo_image = $upload_photo_image['result']['file_name'];
                }
            }

            $user->photo_image = $photo_image;
            if ($user->save()) {                
                return redirect('profile')->with('alert-success', trans('messages.success_save', ['menu' => 'Avatar']));
            } else {
                return redirect('profile')->with('alert-error', trans('messages.failed_save', ['menu' => 'Avatar']));
            }
        } else {
            return redirect('profile')->withErrors($validator)->withInput();
        }
    }

    function password(Request $request) {
        $request->session()->flash('tab', 'password');

        $attributeNames = array(
            'old_password' => 'Password Lama',
            'password' => 'Password Baru',
            'password_confirmation' => 'Konfirmasi Password'
        );

        $validator = Validator::make($request->all(), array(
                    'old_password' => 'required',
                    'password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/|confirmed',
                    'password_confirmation' => 'required'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $old_password = $request->input('old_password');
            $password = $request->input('password');

            if (!(Hash::check($old_password, Auth::user()->password))) {
                return redirect()->back()->with("alert-error", "Password anda tidak cocok dengan password anda saat ini");
            }

            if (strcmp($old_password, $password) == 0) {
                return redirect()->back()->with("alert-error", "Password anda tidak boleh sama dengan password saat ini");
            }

            if (date('Y-m-d') == Auth::user()->password_updated_at) {
                return redirect()->back()->with("alert-error", "Anda sudah mengubah password pada hari ini.");
            }

            $user = Auth::user();
            $user->password = Hash::make($password);
            if ($user->save()) {
                return redirect('profile')->with('alert-success', trans('messages.success_save', ['menu' => 'Password']));
            } else {
                return redirect('profile')->with('alert-error', trans('messages.failed_save', ['menu' => 'Password']));
            }
        } else {
            return redirect('profile')->withErrors($validator)->withInput();
        }
    }

}
