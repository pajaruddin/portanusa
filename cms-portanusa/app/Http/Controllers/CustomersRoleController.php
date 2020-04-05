<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;
use App\Customer_role;
use App\Header;
use App\Libraries\AppConfiguration;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class CustomersRoleController extends Controller {

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $customer_roles = Customer_role::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'name', 'display_name', 'description') 
            ->get();

        return Datatables::of($customer_roles)
            ->addColumn('action', function ($role) {
                $uri = request()->segment(1);
                $menu = $uri;

                $action_button = "";
                $action_button .= '<a href="' . url($menu . "/edit-customer-role", $role->id) . '" class="btn btn-circle btn-icon-only btn-warning" title="Edit Customer Role"><i class="fa fa-pencil"></i></a>';
                $action_button .= '<a href="#" id="delete" data-id="' . $role->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Customer Role"><i class="fa fa-trash"></i></a>';
            
                return $action_button;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function index() {
        $uri = request()->segment(1);
        $data['menu'] = $uri;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;
        
        return view('customer-role.list')->with($data);
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
        return view('customer-role.create')->with($data);
    }

    public function create(Request $request) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'name' => 'Nama',
            'display_name' => 'Tampilan Nama',
            'description' => 'Deskripsi'
        );

        $validator = Validator::make($request->all(), array(
                    'name' => 'required',
                    'display_name' => 'required',
                    'description' => 'required'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $name = $request->input('name');
            $display_name = $request->input('display_name');
            $description = $request->input('description');

            $customer_role = new Customer_role();
            $customer_role->name = $name;
            $customer_role->display_name = $display_name;
            $customer_role->description = $description;
            if ($customer_role->save()) {
                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Kelompok Pelanggan']));

            } else {
                return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', trans('messages.failed_save', ['menu' => 'Kelompok Pelanggan']));
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

        $customer_role = Customer_role::find($id);

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        $data['customer_role'] = $customer_role;
        return view('customer-role.update')->with($data);
    }

    public function update(Request $request, $id) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $customer_role = Customer_role::find($id);

        $attributeNames = array(
            'name' => 'Nama',
            'display_name' => 'Tampilan Nama',
            'description' => 'Deskripsi'
        );

        $validator = Validator::make($request->all(), array(
            'name' => 'required',
            'display_name' => 'required',
            'description' => 'required'
        ));

        $validator->setAttributeNames($attributeNames);
        if (!$validator->fails()) {

            $name = $request->input('name');
            $display_name = $request->input('display_name');
            $description = $request->input('description');

            $customer_role->name = $name;
            $customer_role->display_name = $display_name;
            $customer_role->description = $description;

            if ($customer_role->save()) {
                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Kelompok Pelanggan']));
                
            } else {
                return redirect($menu_uri . "/" . $submenu_uri . "/" . $id)->with('alert-error', trans('messages.failed_save', ['menu' => 'Kelompok Pelanggan']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri . "/" . $id)->withErrors($validator)->withInput();
        }
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $customer_role = Customer_role::find($request->id);

            if ($customer_role->delete()) {

                $request->session()->flash('alert-success', trans('messages.success_delete', ['menu' => 'Kelompok Pelanggan']));
                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_delete', ['menu' => 'Kelompok Pelanggan'])
                ));
            }
        }
    }

}