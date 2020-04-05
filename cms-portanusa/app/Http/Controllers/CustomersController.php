<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;
use App\Customer;
use App\Customer_role;
use App\Header;
use App\Libraries\Shipping;
use App\Libraries\DisplayMenu;
use App\Libraries\AppConfiguration;
use App\Mail\ActivationCustomer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class CustomersController extends Controller {

    function getDetail(Request $request) {
        if ($request->ajax()) {
            $output = array();
            $id = $request->input('id');
            $customer = Customer::find($id);
            if (!empty($customer)) {
                if ($customer->active == 1) {
                    $active = '<span class="label label-success label-sm">Aktif</span>';
                } else {
                    $active = '<span class="label label-danger label-sm">Tidak Aktif</span>';
                }

                $output = array(
                    'id' => $customer->id,
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'email' => $customer->email,
                    'birthday' => date('d/M/Y H:i', strtotime($customer->birthday)),
                    'handphone' => ($customer->handphone != NULL) ? $customer->handphone : "",
                    'gender' => ($customer->gender != NULL) ? $customer->gender : "",
                    'address' => ($customer->address != NULL) ? $customer->address : "",
                    'company_name' => ($customer->company_name != NULL) ? $customer->company_name : "",
                    'active' => $active,
                    'group' => Customer_role::find($customer->customer_role_id)->display_name,
                    'last_login_at' => ($customer->last_login != NULL) ? date('d/M/Y H:i', strtotime($customer->last_login)) : "",
                    'created_at' => date('d/M/Y H:i', strtotime($customer->created_at)),
                    'updated_at' => ($customer->updated_at != NULL) ? date('d/M/Y H:i', strtotime($customer->updated_at)) : ""
                );
            }
            return response()->json($output);
        } else {
            abort(403);
        }
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $customers = Customer::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'customers.id', 'customers.first_name', 'customers.email', 'customer_roles.display_name', 'customers.active')
                ->join('customer_roles', 'customer_roles.id', '=', 'customers.customer_role_id')
                ->orderBy('customers.created_at', 'DESC')
                ->get();

        return Datatables::of($customers)
                ->editColumn('active', function ($customer) {
                    if ($customer->active == 1) {
                        $active_checked = 'checked';
                    } else {
                        $active_checked = '';
                    }
                    $active = '<input type="checkbox" name="active" class="make-switch" data-id="' . $customer->id . '" data-size="mini" ' . $active_checked . '>';
                    
                    if ($customer->active == 1) {
                        $active = '<span class="label label-success label-sm">Aktif</span>';
                    } else {
                        $active = '<input type="checkbox" name="active" class="make-switch" data-id="' . $customer->id . '" data-size="mini" ' . $active_checked . '>';
                    }
                    
                    return $active;
                })
                ->addColumn('action', function ($customer) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="#" id="delete" data-id="' . $customer->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Customer"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['active', 'action'])
                ->make(true);
    }

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;
        
        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;
        
        return view('customer.list')->with($data);
    }

    function updateActive(Request $request) {
        if ($request->ajax()) {
            $customer = Customer::find($request->id);
            $active = $request->input('active');

            // $activation_code = ($active == 1) ? NULL : sha1(md5(microtime()));
            $activation_code = '';


            $customer->activation_code = $activation_code;
            $customer->active = $active;
            
            if ($customer->save()) {

                if($customer->active == 1){
                    Mail::to($customer->email)->send(new ActivationCustomer($customer));
                }

                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_save', ['menu' => 'Pelanggan'])
                ));
            }
        }
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $customer = Customer::find($request->id);

            if ($customer->delete()) {

                $request->session()->flash('alert-success', trans('messages.success_delete', ['menu' => 'Pelanggan']));
                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_delete', ['menu' => 'Pelanggan'])
                ));
            }
        }
    }

}
