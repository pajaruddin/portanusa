<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Product_catalog;
use App\Header;

use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ProductCatalogsController extends Controller {

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        return view('catalog.list')->with($data);
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $catalogs = Product_catalog::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'title', 'publish')
                ->get();

        return Datatables::of($catalogs)
                ->editColumn('active', function ($catalog) {
                    if ($catalog->publish == 'T') {
                        $active_checked = 'checked';
                    } else {
                        $active_checked = '';
                    }
                    $active = '<input type="checkbox" name="active" class="make-switch" data-id="' . $catalog->id . '" data-size="mini" ' . $active_checked . '>';
                    
                    return $active;
                })
                ->addColumn('action', function ($catalog) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/edit-catalogue", $catalog->id) . '" class="btn btn-circle btn-icon-only btn-warning" title="Edit Katalog"><i class="fa fa-pencil"></i></a>';
                    $action_button .= '<a href="#" id="delete" data-id="' . $catalog->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Katalog"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['active', 'action'])
                ->make(true);
    }

    function updateActive(Request $request) {
        if ($request->ajax()) {
            $catalogue = Product_catalog::find($request->id);
            $active = $request->input('active');
            
            $catalogue->publish = $active;
            
            if ($catalogue->save()) {

                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_save', ['menu' => 'Produk Katalog'])
                ));
            }
        }
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $catalogue = Product_catalog::find($request->id);

            if ($catalogue->delete()) {

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
        return view('catalog.create')->with($data);
    }

    public function create(Request $request) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'title' => 'Nama Katalog',
            'document_url' => 'URL Dokumen',
            'thumb_image' => 'Gambar Thumbnail'
        );
        
        $validator = Validator::make($request->all(), array(
            'title' => 'required|unique:product_catalogs',
            'document_url' => 'required',
            'thumb_image' => 'required|mimes:jpeg,png'
        ));

        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $title = $request->input('title');
            $brand_id = $request->input('brand_id');
            $publish = $request->input('publish');
            $document_url = $request->input('document_url');
            $url = strtolower(rand(1111, 9999) . "_" . $title);

            $slug = strtolower(str_slug($title, "_"));
            $image_name = $slug;
            $thumb_image_name = NULL;
            $asset_request = new AssetRequest;

            if ($request->hasFile('thumb_image')) {
                $thumb_file = $request->file('thumb_image');
                $thumb_filename = "thumb_" . $image_name . "_" . uniqid();
                $thumb_full_filename = $thumb_filename . "." . $thumb_file->getClientOriginalExtension();
                $thumb_filetype = $thumb_file->getClientMimeType();
                $thumb_filepath = $_FILES['thumb_image']['tmp_name'];
                $destination_path = AppConfiguration::catalogsThumbPath()->value;

                $upload_thumb_image = $asset_request->upload($thumb_filepath, $thumb_filetype, $thumb_full_filename, $destination_path, $thumb_filename);
                if ($upload_thumb_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri . "/" . $action_uri)->with('alert-error', $upload_thumb_image['description']);
                } else {
                    $thumb_image_name = $upload_thumb_image['result']['file_name'];
                }
            }

            $catalogue = new Product_catalog();
            $catalogue->title = $title;
            $catalogue->document_url = $document_url;
            $catalogue->url = $url;
            $catalogue->thumb_image = $thumb_image_name;
            $catalogue->publish = ($publish != "") ? "T" : "F";

            if ($catalogue->save()) {
                $menu = $menu_uri;

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Produk Katalog']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', trans('messages.failed_save', ['menu' => 'Produk Katalog']));
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

        $catalogue = Product_catalog::find($id);

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        $data['catalogue'] = $catalogue;
        return view('catalog.update')->with($data);
    }

    public function update(Request $request, $id) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'title' => 'Judul Katalog',
            'document_url' => 'Dokumen URL',
            'thumb_image' => 'Gambar Thumbnail'
        );

        $catalogue = Product_catalog::find($id);

        if ($catalogue->title == $request->title) {
            $is_unique = "";
        } else {
            $is_unique = "|unique:product_catalogs";
        }

        $validator = Validator::make($request->all(), array(
                    'title' => 'required' . $is_unique,
                    'document_url' => 'required',
                    'thumb_image' => 'mimes:jpeg,png'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $title = $request->input('title');
            $document_url = $request->input('document_url');
            $publish = $request->input('publish');
            $url = strtolower(rand(1111, 9999) . "_" . $title);

            $slug = strtolower(str_slug($title, "_"));
            $image_name = $slug;
            $thumb_image = $catalogue->thumb_image;
            $asset_request = new AssetRequest;

            if ($request->hasFile('thumb_image')) {
                $thumb_file = $request->file('thumb_image');
                $thumb_filename = "thumb_" . $image_name . "_" . uniqid();
                $thumb_full_filename = $thumb_filename . "." . $thumb_file->getClientOriginalExtension();
                $thumb_filetype = $thumb_file->getClientMimeType();
                $thumb_filepath = $_FILES['thumb_image']['tmp_name'];
                $destination_path = AppConfiguration::catalogsThumbPath()->value;
                
                $upload_thumb_image = $asset_request->upload($thumb_filepath, $thumb_filetype, $thumb_full_filename, $destination_path, $thumb_filename);
                if ($upload_thumb_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri . "/" . $action_uri)->with('alert-error', $upload_thumb_image['description']);
                } else {
                    if ($catalogue->thumb_image != NULL) {
                        $asset_request->delete($destination_path, $catalogue->thumb_image);
                    }
                    $thumb_image = $upload_thumb_image['result']['file_name'];
                }
            }


            $catalogue->title = $title;
            $catalogue->thumb_image = $thumb_image;
            $catalogue->document_url = $document_url;
            $catalogue->url = $url;
            $catalogue->publish = ($publish != "") ? "T" : "F";

            if ($catalogue->save()) {

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Produk Katalog']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', trans('messages.failed_save', ['menu' => 'Produk Katalog']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->withErrors($validator)->withInput();
        }
    }

}