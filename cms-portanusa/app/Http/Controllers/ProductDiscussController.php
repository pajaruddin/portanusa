<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Product_discussion;
use App\Header;

use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ProductDiscussController extends Controller {

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        return view('product-discuss.list')->with($data);
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $product_discussions = Product_discussion::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'product_discussions.id', 'customers.first_name', 'text', 'product_discussions.publish', 'products.name')
                ->leftJoin('customers', 'customers.id', '=', 'product_discussions.customer_id')
                ->leftJoin('products', 'products.id', '=', 'product_discussions.product_id')
                ->whereNull('parent')
                ->get();

        return Datatables::of($product_discussions)
                ->editColumn('count', function ($discuss) {
                    $child_discuss = Product_discussion::where('parent', $discuss->id)->where('publish', 'F')->get();
                    $count = count($child_discuss);
                    return $count;
                })
                ->editColumn('active', function ($discuss) {
                    if ($discuss->publish == 'T') {
                        $active_checked = 'checked';
                    } else {
                        $active_checked = '';
                    }
                    $active = '<input type="checkbox" name="active" class="make-switch" data-id="' . $discuss->id . '" data-size="mini" ' . $active_checked . '>';
                    
                    return $active;
                })
                ->addColumn('action', function ($discuss) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/list-child-discuss", $discuss->id) . '" class="btn btn-circle btn-icon-only btn-primary" title="List Child"><i class="fa fa-list"></i></a>';
                    $action_button .= '<a href="' . url($menu . "/edit-product-discuss", $discuss->id) . '" class="btn btn-circle btn-icon-only btn-warning" title="Edit Pesan"><i class="fa fa-pencil"></i></a>';
                    $action_button .= '<a href="#" id="delete" data-id="' . $discuss->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Pesan"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['active', 'action'])
                ->make(true);
    }

    function updateActive(Request $request) {
        if ($request->ajax()) {
            $product_discussions = Product_discussion::find($request->id);
            $active = $request->input('active');
            
            $product_discussions->publish = $active;
            
            if ($product_discussions->save()) {

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
        
            $product_discussions = Product_discussion::find($request->id);

            if ($product_discussions->delete()) {

                $discussions = Product_discussion::where('parent', $product_discussions->id)->get();
                if(count($discussions) > 0){
                    foreach($discussions as $discuss) {
                        $delete = $discuss->delete();
                    }
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

    function edit($id) {
        $menu_uri = request()->segment(1);
        $menu = $menu_uri;

        $submenu_uri = request()->segment(2);
        $submenu = $submenu_uri;

        $product_discuss = Product_discussion::select('product_discussions.id', 'customers.first_name', 'text', 'product_discussions.publish', 'products.name')
                    ->leftJoin('customers', 'customers.id', '=', 'product_discussions.customer_id')
                    ->leftJoin('products', 'products.id', '=', 'product_discussions.product_id')
                    ->where('product_discussions.id', $id)
                    ->first();

        $child_discussion = Product_discussion::select('product_discussions.id', 'product_discussions.customer_id', 'product_discussions.user_id', 'customers.first_name', 'text', 'product_discussions.publish', 'users.first_name as user_name')
                    ->leftJoin('customers', 'customers.id', '=', 'product_discussions.customer_id')
                    ->leftJoin('users', 'users.id', '=', 'product_discussions.user_id')
                    ->where('parent', $id)
                    ->get();

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        $data['product_discuss'] = $product_discuss;
        $data['child_discuss'] = $child_discussion;
        return view('product-discuss.update')->with($data);
    }

    public function update(Request $request, $id) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'text' => 'Pesan',
        );

        $discussion = Product_discussion::find($id);

        $validator = Validator::make($request->all(), array(
            'text' => 'required'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $text = $request->input('text');

            $discuss = new Product_discussion();
            $discuss->parent = $discussion->id;
            $discuss->product_id = $discussion->product_id;
            $discuss->user_id = Auth::id();
            $discuss->text = $text;
            $discuss->publish = 'T';

            if ($discuss->save()) {

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Bank']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', trans('messages.failed_save', ['menu' => 'Bank']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->withErrors($validator)->withInput();
        }
    }

    function list($id) {
        $uri = request()->segment(1);
        $menu = $uri;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['id'] = $id;
        $data['menu'] = $menu;
        return view('product-discuss.list_child')->with($data);
    }

    function getChildLists($id) {
        DB::statement(DB::raw('set @rownum=0'));
        $product_discussions = Product_discussion::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'product_discussions.id', 'customers.first_name', 'text', 'product_discussions.publish', 'products.name')
                ->leftJoin('customers', 'customers.id', '=', 'product_discussions.customer_id')
                ->leftJoin('products', 'products.id', '=', 'product_discussions.product_id')
                ->where('parent', $id)
                ->whereNull('user_id')
                ->get();

        return Datatables::of($product_discussions)
                ->editColumn('count', function ($discuss) {
                    $child_discuss = Product_discussion::where('parent', $discuss->id)->where('publish', 'F')->get();
                    $count = count($child_discuss);
                    return $count;
                })
                ->editColumn('active', function ($discuss) {
                    if ($discuss->publish == 'T') {
                        $active_checked = 'checked';
                    } else {
                        $active_checked = '';
                    }
                    $active = '<input type="checkbox" name="active" class="make-switch" data-id="' . $discuss->id . '" data-size="mini" ' . $active_checked . '>';
                    
                    return $active;
                })
                ->addColumn('action', function ($discuss) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/edit-product-discuss", $discuss->id) . '" class="btn btn-circle btn-icon-only btn-warning" title="Edit Pesan"><i class="fa fa-pencil"></i></a>';
                    $action_button .= '<a href="#" id="delete" data-id="' . $discuss->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Pesan"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['active', 'action'])
                ->make(true);
    }

}