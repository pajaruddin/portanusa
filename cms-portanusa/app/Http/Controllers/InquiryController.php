<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;
use App\Inquiry;
use App\Header;

use App\Libraries\Shipping;
use App\Libraries\AppConfiguration;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class InquiryController extends Controller {

    function getDetail(Request $request) {
        if ($request->ajax()) {
            $output = array();
            $id = $request->input('id');
            $inquiry = Inquiry::find($id);
            if (!empty($inquiry)) {

                // Location
                $data_provinces = array();
                $provinces = Shipping::getCities($inquiry->province_id);
                if ($provinces['rajaongkir']['status']['code'] == 200) {
                    $data_provinces = $provinces['rajaongkir']['results'];
                }

                $data_location = array();
                if(!empty($data_provinces)){
                    foreach($data_provinces as $province){
                        if($province['city_id'] == $inquiry->state_id){
                            $data_location = array(
                                'city_id' => $province['city_id'],
                                'province_id' => $province['province_id'],
                                'province' => $province['province'],
                                'type' => $province['type'],
                                'city_name' => $province['city_name'],
                                'postal_code' => $province['postal_code']
                            );
                        }
                    }
                }

                $output = array(
                    'id' => $inquiry->id,
                    'full_name' => $inquiry->full_name,
                    'company_name' => ($inquiry->company_name != NULL) ? $inquiry->company_name : "-",
                    'email' => $inquiry->email,
                    'handphone' => ($inquiry->handphone != NULL) ? $inquiry->handphone : "-",
                    'telephone' => ($inquiry->telephone != NULL) ? $inquiry->telephone : "-",
                    'person_as' => ($inquiry->person_as != NULL) ? $inquiry->person_as : "-",
                    'address' => ($inquiry->address != NULL) ? $inquiry->address : "",
                    'province' => $data_location['province'],
                    'city_name' => $data_location['city_name'],
                    // 'province' => "Jakarta Barat",
                    // 'city_name' => "Senayan",
                    'postal_zip' => $inquiry->postal_zip,
                    'message' => ($inquiry->message != NULL) ? $inquiry->message : "",
                    'created_at' => date('d M Y H:i:s', strtotime($inquiry->created_at)),
                );
            }
            return response()->json($output);
        } else {
            abort(403);
        }
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $inquiries = Inquiry::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'full_name', 'person_as')
                ->orderBy('inquiries.created_at', 'DESC')
                ->get();

        return Datatables::of($inquiries)
                ->addColumn('action', function ($inquiry) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="#" id="delete" data-id="' . $inquiry->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Inquiry"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['action'])
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

        return view('inquiry.list')->with($data);
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $inquiry = Inquiry::find($request->id);

            if ($inquiry->delete()) {

                $request->session()->flash('alert-success', trans('messages.success_delete', ['menu' => 'Inquiry']));
                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_delete', ['menu' => 'Inquiry'])
                ));
            }
        }
    }

}
