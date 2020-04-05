<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Catalog;
use App\Header;

use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class CatalogController extends Controller {

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        return view('e-catalog.list')->with($data);
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $catalogs = Catalog::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'title')
                ->get();

        return Datatables::of($catalogs)
                ->addColumn('action', function ($catalog) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/edit-catalog", $catalog->id) . '" class="btn btn-circle btn-icon-only btn-warning" title="Edit E-Catalog"><i class="fa fa-pencil"></i></a>';
                    $action_button .= '<a href="#" id="delete" data-id="' . $catalog->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete E-Catalog"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $catalog = Catalog::find($request->id);

            if ($catalog->delete()) {
                $asset_request = new AssetRequest;
                if ($catalog->file_catalog != NULL) {
                    $destination_path = AppConfiguration::catalogPath()->value;
                    $asset_request->delete($destination_path, $catalog->file_catalog);
                }

                $request->session()->flash('alert-success', trans('messages.success_delete', ['menu' => 'E-Catalog']));
                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_delete', ['menu' => 'E-Catalog'])
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
        return view('e-catalog.create')->with($data);
    }

    public function create(Request $request) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'title' => 'Judul Katalog',
            'file_catalog' => 'File PDF'
        );

        $validator = Validator::make($request->all(), array(
            'title' => 'required|unique:catalogs',
            'file_catalog' => 'required|mimes:pdf'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $title = $request->input('title');

            $slug = strtolower(str_slug($title, "_"));
            $file_catalog = NULL;
            $asset_request = new AssetRequest;

            if ($request->hasFile('file_catalog')) {
                $catalog_file = $request->file('file_catalog');
                $catalog_filename = "catalog_" . $slug . "_" . uniqid();
                $catalog_full_filename = "catalog_" . $slug . "_" . uniqid() . "." . $catalog_file->getClientOriginalExtension();
                $catalog_filetype = $catalog_file->getClientMimeType();
                $catalog_filepath = $_FILES['file_catalog']['tmp_name'];
                $destination_path = AppConfiguration::catalogPath()->value;

                $upload_file = $asset_request->upload($catalog_filepath, $catalog_filetype, $catalog_full_filename, $destination_path, $catalog_filename);
                if ($upload_file['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', $upload_file['description']);
                } else {
                    $file_catalog = $upload_file['result']['file_name'];
                }
            }

            $catalog = new Catalog();
            $catalog->title = $title;
            $catalog->file_catalog = $file_catalog;
            
            if ($catalog->save()) {
                $menu = $menu_uri;

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'E-Catalog']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', trans('messages.failed_save', ['menu' => 'E-Catalog']));
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

        $catalog = Catalog::find($id);

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        $data['catalog'] = $catalog;
        return view('e-catalog.update')->with($data);
    }

    public function update(Request $request, $id) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'title' => 'Judul Katalog',
            'file_catalog' => 'File PDF'
        );

        $validator = Validator::make($request->all(), array(
            'title' => 'required',
            'file_catalog' => 'mimes:pdf'
        ));

        $catalog = Catalog::find($id);

        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $title = $request->input('title');

            $slug = strtolower(str_slug($title, "_"));
            $file_catalog = $catalog->file_catalog;
            $asset_request = new AssetRequest;

            if ($request->hasFile('file_catalog')) {
                $catalog_file = $request->file('file_catalog');
                $catalog_filename = "catalog_" . $slug . "_" . uniqid();
                $catalog_full_filename = "catalog_" . $slug . "_" . uniqid() . "." . $catalog_file->getClientOriginalExtension();
                $catalog_filetype = $catalog_file->getClientMimeType();
                $catalog_filepath = $_FILES['file_catalog']['tmp_name'];
                $destination_path = AppConfiguration::catalogPath()->value;

                $upload_file = $asset_request->upload($catalog_filepath, $catalog_filetype, $catalog_full_filename, $destination_path, $catalog_filename);
                if ($upload_file['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', $upload_file['description']);
                } else {
                    if ($catalog->file_catalog != NULL) {
                        $asset_request->delete($destination_path, $catalog->file_catalog);
                    }
                    $file_catalog = $upload_file['result']['file_name'];
                }
            }

            $catalog->title = $title;
            $catalog->file_catalog = $file_catalog;
            
            if ($catalog->save()) {
                $menu = $menu_uri;

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'E-Catalog']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', trans('messages.failed_save', ['menu' => 'E-Catalog']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->withErrors($validator)->withInput();
        }
    }
}