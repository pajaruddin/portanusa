<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Voucher;
use App\Header;

use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class VoucherController extends Controller {

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        return view('voucher.list')->with($data);
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $vouchers = Voucher::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'name', 'code', 'date_start', 'date_end')
                ->get();

        return Datatables::of($vouchers)
                ->editColumn('date_start', function ($voucher) {
                    return date('d/m/Y H:i:s', strtotime($voucher->date_start));
                })
                ->editColumn('date_end', function ($voucher) {
                    return date('d/m/Y H:i:s', strtotime($voucher->date_end));
                })
                ->addColumn('action', function ($voucher) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/edit-voucher", $voucher->id) . '" class="btn btn-circle btn-icon-only btn-warning" title="Edit Voucher"><i class="fa fa-pencil"></i></a>';
                    $action_button .= '<a href="#" id="delete" data-id="' . $voucher->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Voucher"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $voucher = Voucher::find($request->id);

            if ($voucher->delete()) {
                $asset_request = new AssetRequest;
                if ($voucher->banner != NULL) {
                    $destination_path = AppConfiguration::voucherBannerImagePath()->value;
                    $asset_request->delete($destination_path, $voucher->banner);
                }

                $request->session()->flash('alert-success', trans('messages.success_delete', ['menu' => 'Voucher']));
                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_delete', ['menu' => 'Voucher'])
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
        return view('voucher.create')->with($data);
    }

    public function create(Request $request) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'name' => 'Nama Voucher',
            'code' => 'Code Voucher',
            'description_indonesia' => 'Deskripsi',
            'minimum_amount' => 'Minimum Pembayaran',
            'discount' => 'Diskom',
            'banner_image' => 'Gambar Banner',
            'date_start' => 'Tanggal Mulai',
            'date_end' => 'Tanggal Berakhir'
        );

        $validator = Validator::make($request->all(), array(
            'name' => 'required|unique:vouchers',
            'code' => 'required',
            'description_indonesia' => 'required',
            'minimum_amount' => 'required',
            'discount' => 'required',
            'banner_image' => 'required|mimes:jpg,jpeg,png',
            'date_start' => 'required',
            'date_end' => 'required'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $name = $request->input('name');
            $code = $request->input('code');
            $minimum_amount = $request->input('minimum_amount');
            $discount = $request->input('discount');
            $description_indonesia = $request->input('description_indonesia');
            $date_start = $request->input('date_start');
            $date_end = $request->input('date_end');

            $slug = strtolower(str_slug($name, "_"));
            $banner_image = NULL;
            $asset_request = new AssetRequest;

            if ($request->hasFile('banner_image')) {
                $banner_image_file = $request->file('banner_image');
                $banner_filename = "banner_" . $slug . "_" . uniqid();
                $banner_full_filename = "banner_" . $slug . "_" . uniqid() . "." . $banner_image_file->getClientOriginalExtension();
                $banner_filetype = $banner_image_file->getClientMimeType();
                $banner_filepath = $_FILES['banner_image']['tmp_name'];
                $destination_path = AppConfiguration::voucherBannerImagePath()->value;

                $upload_banner_image = $asset_request->upload($banner_filepath, $banner_filetype, $banner_full_filename, $destination_path, $banner_filename);
                if ($upload_banner_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', $upload_banner_image['description']);
                } else {
                    $banner_image = $upload_banner_image['result']['file_name'];
                }
            }

            $voucher = new Voucher();
            $voucher->name = $name;
            $voucher->code = $code;
            $voucher->description = ($description_indonesia != "") ? $description_indonesia : NULL;
            $voucher->minimum_amount = str_replace(".", "", $minimum_amount);
            $voucher->discount = $discount;
            $voucher->banner = $banner_image;
            $voucher->date_start = date('Y-m-d H:i:s', strtotime($date_start));
            $voucher->date_end = date('Y-m-d H:i:s', strtotime($date_end));
            if ($voucher->save()) {
                $menu = $menu_uri;

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Voucher']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', trans('messages.failed_save', ['menu' => 'Voucher']));
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

        $voucher = Voucher::find($id);

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        $data['voucher'] = $voucher;
        return view('voucher.update')->with($data);
    }

    public function update(Request $request, $id) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'name' => 'Nama Voucher',
            'code' => 'Code Voucher',
            'description_indonesia' => 'Deskripsi',
            'minimum_amount' => 'Minimum Pembayaran',
            'discount' => 'Diskom',
            'banner_image' => 'Gambar Banner',
            'date_start' => 'Tanggal Mulai',
            'date_end' => 'Tanggal Berakhir'
        );

        $voucher = Voucher::find($id);

        if ($voucher->name == $request->name) {
            $is_unique = "";
        } 
        else {
            $is_unique = "|unique:vouchers";
        }

        if ($voucher->banner != null){
            $is_required = "";
        }
        else{
            $is_required = "required";
        }

        $validator = Validator::make($request->all(), array(
                    'name' => 'required' . $is_unique,
                    'code' => 'required',
                    'description_indonesia' => 'required',
                    'minimum_amount' => 'required',
                    'discount' => 'required',
                    'banner_image' => $is_required . '|mimes:jpg,jpeg,png',
                    'date_start' => 'required',
                    'date_end' => 'required'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $name = $request->input('name');
            $code = $request->input('code');
            $minimum_amount = $request->input('minimum_amount');
            $discount = $request->input('discount');
            $description_indonesia = $request->input('description_indonesia');
            $date_start = $request->input('date_start');
            $date_end = $request->input('date_end');

            $slug = strtolower(str_slug($name, "_"));
            $banner_image = $voucher->banner;
            $asset_request = new AssetRequest;


            if ($request->hasFile('banner_image')) {
                $banner_image_file = $request->file('banner_image');
                $banner_filename = "banner_" . $slug . "_" . uniqid();
                $banner_full_filename = "banner_" . $slug . "_" . uniqid() . "." . $banner_image_file->getClientOriginalExtension();
                $banner_filetype = $banner_image_file->getClientMimeType();
                $banner_filepath = $_FILES['banner_image']['tmp_name'];
                $destination_path = AppConfiguration::voucherBannerImagePath()->value;

                $upload_banner_image = $asset_request->upload($banner_filepath, $banner_filetype, $banner_full_filename, $destination_path, $banner_filename);
                if ($upload_banner_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', $upload_banner_image['description']);
                } else {
                    if ($voucher->banner != NULL) {
                        $asset_request->delete($destination_path, $voucher->banner);
                    }
                    $banner_image = $upload_banner_image['result']['file_name'];
                }
            }

            $voucher->name = $name;
            $voucher->code = $code;
            $voucher->description = ($description_indonesia != "") ? $description_indonesia : NULL;
            $voucher->minimum_amount = str_replace(".", "", $minimum_amount);
            $voucher->discount = $discount;
            $voucher->banner = $banner_image;
            $voucher->date_start = date('Y-m-d H:i:s', strtotime($date_start));
            $voucher->date_end = date('Y-m-d H:i:s', strtotime($date_end));

            if ($voucher->save()) {

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Voucher']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', trans('messages.failed_save', ['menu' => 'Voucher']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->withErrors($validator)->withInput();
        }
    }

}