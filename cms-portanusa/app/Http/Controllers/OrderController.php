<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Order;
use App\Order_product;
use App\Order_status;
use App\Customer;
use App\Header;

use App\Libraries\Shipping;
use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;

use App\Mail\OrderAcceptPayment;
use App\Mail\OrderFailed;
use App\Mail\OrderShipping;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class OrderController extends Controller {
    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;
        
        return view('order.list')->with($data);
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $orders = Order::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'orders.id', 'customers.first_name', 'customers.last_name', 'orders.invoice_no', 'orders.total_price', 'orders.transfer_image', 'orders.created_at', 'order_status.status', 'order_status.label')
                ->join('customers', 'customers.id', '=', 'orders.customer_id')
                ->join('order_status', 'order_status.id', '=', 'orders.status')
                ->where('orders.status', 2)
                ->get();

        return Datatables::of($orders)
                ->editColumn('total_price', function($order) {
                    if ($order->total_price != null) {
                        $price = number_format($order->total_price, 0, ",", ".");
                        $detail_price = "Rp. " . $price;
                        return $detail_price;
                    }
                })
                ->editColumn('first_name', function($order) {
                    $fullname = $order->first_name . ' ' . $order->last_name;
                    return $fullname;
                })
                ->editColumn('transfer_image', function($order) {
                    $transfer_image = $order->transfer_image;
                    $status_transfer = null;
                    if($transfer_image == null){
                        $status_transfer = '<span class="label label-danger label-sm">Belum Dibayar</span>';
                    }
                    else{
                        $status_transfer = '<span class="label label-success label-sm">Sudah Dibayar</span>';
                    }
                    return $status_transfer;
                })
                ->editColumn('status', function($order) {
                    $status = '<span class="label label-'. $order->label .' label-sm">'.  $order->status  .'</span>';
                    return $status;
                })
                ->editColumn('created_at', function ($order) {
                    return date('d M Y H:i:s', strtotime($order->created_at));
                })
                ->addColumn('action', function ($order) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/detail", $order->id) . '" class="btn btn-circle btn-icon-only btn-primary" title="Detail Produk"><i class="fa fa-search"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['status', 'transfer_image', 'action'])
                ->make(true);
    }

    function payment() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        return view('order.list_payment')->with($data);
    }

    function getListPayments() {
        DB::statement(DB::raw('set @rownum=0'));
        $orders = Order::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'orders.id', 'customers.first_name', 'customers.last_name', 'orders.invoice_no', 'orders.total_price', 'orders.updated_at', 'order_status.status', 'order_status.label')
                ->join('customers', 'customers.id', '=', 'orders.customer_id')
                ->join('order_status', 'order_status.id', '=', 'orders.status')
                ->where('orders.status', 3)
                ->get();

        return Datatables::of($orders)
                ->editColumn('total_price', function($order) {
                    if ($order->total_price != null) {
                        $price = number_format($order->total_price, 0, ",", ".");
                        $detail_price = "Rp. " . $price;
                        return $detail_price;
                    }
                })
                ->editColumn('first_name', function($order) {
                    $fullname = $order->first_name . ' ' . $order->last_name;
                    return $fullname;
                })
                ->editColumn('status', function($order) {
                    $status = '<span class="label label-'. $order->label .' label-sm">'.  $order->status  .'</span>';
                    return $status;
                })
                ->editColumn('updated_at', function ($order) {
                    return date('d M Y H:i:s', strtotime($order->updated_at));
                })
                ->addColumn('action', function ($order) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/detail", $order->id) . '" class="btn btn-circle btn-icon-only btn-primary" title="Detail Produk"><i class="fa fa-search"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
    }

    function failed() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        return view('order.list_failed')->with($data);
    }

    function getListFaileds() {
        DB::statement(DB::raw('set @rownum=0'));
        $orders = Order::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'orders.id', 'customers.first_name', 'customers.last_name', 'orders.invoice_no', 'orders.total_price', 'orders.created_at', 'order_status.status', 'order_status.label')
                ->join('customers', 'customers.id', '=', 'orders.customer_id')
                ->join('order_status', 'order_status.id', '=', 'orders.status')
                ->where('orders.status', 1)
                ->get();

        return Datatables::of($orders)
                ->editColumn('total_price', function($order) {
                    if ($order->total_price != null) {
                        $price = number_format($order->total_price, 0, ",", ".");
                        $detail_price = "Rp. " . $price;
                        return $detail_price;
                    }
                })
                ->editColumn('first_name', function($order) {
                    $fullname = $order->first_name . ' ' . $order->last_name;
                    return $fullname;
                })
                ->editColumn('status', function($order) {
                    $status = '<span class="label label-'. $order->label .' label-sm">'.  $order->status  .'</span>';
                    return $status;
                })
                ->editColumn('created_at', function ($order) {
                    return date('d M Y H:i:s', strtotime($order->created_at));
                })
                ->addColumn('action', function ($order) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/detail", $order->id) . '" class="btn btn-circle btn-icon-only btn-primary" title="Detail Produk"><i class="fa fa-search"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
    }

    function shipping() {
        $uri = request()->segment(1);
        $menu = $uri;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        return view('order.list_shipping')->with($data);
    }

    function getListShippings() {
        DB::statement(DB::raw('set @rownum=0'));
        $orders = Order::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'orders.id', 'customers.first_name', 'customers.last_name', 'orders.invoice_no', 'orders.total_price', 'orders.updated_at', 'order_status.status', 'order_status.label')
                ->join('customers', 'customers.id', '=', 'orders.customer_id')
                ->join('order_status', 'order_status.id', '=', 'orders.status')
                ->where('orders.status', 4)
                ->get();

        return Datatables::of($orders)
                ->editColumn('total_price', function($order) {
                    if ($order->total_price != null) {
                        $price = number_format($order->total_price, 0, ",", ".");
                        $detail_price = "Rp. " . $price;
                        return $detail_price;
                    }
                })
                ->editColumn('first_name', function($order) {
                    $fullname = $order->first_name . ' ' . $order->last_name;
                    return $fullname;
                })
                ->editColumn('status', function($order) {
                    $status = '<span class="label label-'. $order->label .' label-sm">'.  $order->status  .'</span>';
                    return $status;
                })
                ->editColumn('updated_at', function ($order) {
                    return date('d M Y H:i:s', strtotime($order->updated_at));
                })
                ->addColumn('action', function ($order) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/detail", $order->id) . '" class="btn btn-circle btn-icon-only btn-primary" title="Detail Produk"><i class="fa fa-search"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
    }

    function success() {
        $uri = request()->segment(1);
        $menu = $uri;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        return view('order.list_success')->with($data);
    }

    function getListSuccess() {
        DB::statement(DB::raw('set @rownum=0'));
        $orders = Order::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'orders.id', 'customers.first_name', 'customers.last_name', 'orders.invoice_no', 'orders.total_price', 'orders.updated_at', 'order_status.status', 'order_status.label')
                ->join('customers', 'customers.id', '=', 'orders.customer_id')
                ->join('order_status', 'order_status.id', '=', 'orders.status')
                ->where('orders.status', 5)
                ->get();

        return Datatables::of($orders)
                ->editColumn('total_price', function($order) {
                    if ($order->total_price != null) {
                        $price = number_format($order->total_price, 0, ",", ".");
                        $detail_price = "Rp. " . $price;
                        return $detail_price;
                    }
                })
                ->editColumn('first_name', function($order) {
                    $fullname = $order->first_name . ' ' . $order->last_name;
                    return $fullname;
                })
                ->editColumn('status', function($order) {
                    $status = '<span class="label label-'. $order->label .' label-sm">'.  $order->status  .'</span>';
                    return $status;
                })
                ->editColumn('updated_at', function ($order) {
                    return date('d M Y H:i:s', strtotime($order->updated_at));
                })
                ->addColumn('action', function ($order) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/detail", $order->id) . '" class="btn btn-circle btn-icon-only btn-primary" title="Detail Produk"><i class="fa fa-search"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
    }


    public function update(Request $request, $id) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'status' => 'Status Order',
            'awb_number' => 'No Resi'
        );

        $order = Order::find($id);
        $status = $request->input('status');

        if ($status != 4) {
            $is_required = '';
        } else {
            $is_required = 'required';
        }

        $validator = Validator::make($request->all(), array(
            'status' => 'required',
            'awb_number' => $is_required
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $awb_number = $request->input('awb_number');
            $title = $order->invoice_no;

            $slug = strtolower(str_slug($title, "_"));
            $file_tax = NULL;
            $asset_request = new AssetRequest;

            if ($request->hasFile('file_tax')) {
                $catalog_file = $request->file('file_tax');
                $catalog_filename = "tax_" . $slug . "_" . uniqid();
                $catalog_full_filename = "tax_" . $slug . "_" . uniqid() . "." . $catalog_file->getClientOriginalExtension();
                $catalog_filetype = $catalog_file->getClientMimeType();
                $catalog_filepath = $_FILES['file_tax']['tmp_name'];
                $destination_path = AppConfiguration::taxPath()->value;

                $upload_file = $asset_request->upload($catalog_filepath, $catalog_filetype, $catalog_full_filename, $destination_path, $catalog_filename);
                if ($upload_file['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', $upload_file['description']);
                } else {
                    $file_tax = $upload_file['result']['file_name'];
                }
            }

            $order->status = $status;
            $order->awb_number = $awb_number;
            $order->file_tax = $file_tax;

            if ($order->save()) {

                $customer = Customer::find($order->customer_id);

                // Order
                $orders = Order::select('orders.*', 'order_status.status as status_order', 'order_status.label')
                        ->leftJoin('order_status', 'order_status.id', '=', 'orders.status')
                        ->where('orders.id', $id)
                        ->first();

                $order_products = Order_product::where('order_id', $id)->get();
                $price_list = 0;
                if(count($order_products) > 0) {
                    foreach($order_products as $product) {
                        $price_list += $product->price;
                    }
                }

                if($status == 3){
                    Mail::to($customer->email)->send(new OrderAcceptPayment($orders, $customer, $order_products, $price_list));
                }
                elseif($status == 1){
                    Mail::to($customer->email)->send(new OrderFailed($orders, $customer));
                }
                elseif($status == 4){
                    Mail::to($customer->email)->send(new OrderShipping($orders, $customer));
                }
                

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Order']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', trans('messages.failed_save', ['menu' => 'Order']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->withErrors($validator)->withInput();
        }
    }

    function getDetailOrder($id) {
        $menu_uri = request()->segment(1);
        $menu = $menu_uri;

        $submenu_uri = request()->segment(2);
        $submenu = $submenu_uri;
        
        // Order
        $order = Order::select('orders.*', 'order_status.status as status_order', 'order_status.label')
                ->leftJoin('order_status', 'order_status.id', '=', 'orders.status')
                ->where('orders.id', $id)
                ->first();

        $customer = Customer::find($order->customer_id);
        $order_products = Order_product::where('order_id', $id)->get();
        $price_list = 0;
        if(count($order_products) > 0) {
            foreach($order_products as $product) {
                $price_list += $product->price;
            }
        }

        $domain_asset = AppConfiguration::assetPortalDomain()->value;
        $transfer_path = AppConfiguration::transferPath()->value;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        $data['order'] = $order;
        $data['customer'] = $customer;
        $data['order_products'] = $order_products;
        $data['price'] = $price_list;
        $data['domain_asset'] = $domain_asset;
        $data['transfer_path'] = $transfer_path;
        return view('order.detail')->with($data);
    }
    
}