<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Order;
use App\Order_product;
use App\Order_status;
use App\Customer;
use App\Product;
use App\Header;

use App\Exports\OrderExport;

use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class ReportsController extends Controller {

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $order_status = Order_status::All();

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['order_status'] = $order_status;
        return view('report.order.create')->with($data);
    }

    public function download(Request $request) {
        $menu_uri = request()->segment(1);

        $attributeNames = array(
            'export_date' => 'Tanggal',
            'status' => 'Status'
        );

        $validator = Validator::make($request->all(), array(
            'export_date' => 'required',
            'status' => 'required'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {

            $export_month = date('Y-m-d', strtotime($request->input('export_date')));
            $export_year = date('Y-m-d', strtotime($request->input('export_date')));
            $status = $request->input('status');
            
            $data = [
                'export_month' => $export_month,
                'export_year' => $export_year,
                'status' => $status
            ];
            ob_end_clean();
            return (new OrderExport($data))->download('Order.xlsx'); 
        } else {
            return redirect($menu_uri)->withErrors($validator)->withInput();
        }
    }

}