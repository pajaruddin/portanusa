<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

use Validator;
use AppConfiguration;
use App\Voucher;
use App\Order;

class VouchersController extends Controller
{
    public function index(){
        $data['asset_domain'] = AppConfiguration::assetDomain()->value;
        $data['VoucherImagePath'] = AppConfiguration::VoucherImagePath()->value;
        $today = date('Y-m-d');

        $data['vouchers'] = Voucher::where('date_start', '<=', $today)->where('date_end', '>=', $today)->get();
        $data['title'] = "Portanusa - Voucher";
        return view('voucher.page')->with($data);
    }


    public function check(Request $request) {
        
        if($request->ajax()){
            $attributeNames = array(
                'total_price' => "Total Price",
                'voucher' => "Code Voucher"
            );

            $validator = Validator::make($request->all(), array(
                        'total_price' => 'required',
                        'voucher' => 'required'
            ));

            $validator->setAttributeNames($attributeNames);

            $status = "";
            $message = "";

            if (!$validator->fails()) {

                $total_price = $request->input('total_price');
                $voucher = $request->input('voucher');

                $today = date('Y-m-d H:i:s');

                $voucher = Voucher::where('code',$voucher)->first();
                if(!empty($voucher)){
                    if(($voucher->date_start <= $today && $voucher->date_end >= $today)){
                        if($voucher->minimum_amount <= $total_price){
                            $user = Auth::user();
                            $voucher_order = Order::where('customer_id', $user->id)->where('voucher_code', $voucher->code)->first();
                            if(!empty($voucher_order)){
                                $status = "error";
                                $message = "Voucher cant use twice";
                            }else{
                                $discount = $voucher->discount;

                                $request->session()->put('orderVoucher.code', $voucher->code);
                                $request->session()->put('orderVoucher.discount', $discount);

                                $status = "success";
                                $message = "Voucher successfully used";
                            }
                        }else{
                            $status = "error";
                            $message = "Your total transaction is less than the minimum requirement";
                        }
                    }else{
                        $status = "error";
                        $message = "Voucher expired";
                    }
                }else{
                    $status = "error";
                    $message = "Voucher code not found";
                }

            }else{
                $status = "error";
                $message = "";
                $errors = $validator->errors();
                foreach ($errors->all() as $error_message) {
                    $message .= $error_message . "<br/>";
                }

            }

            return response()->json(array(
                'status' => $status,
                'message'=> $message
            ));

        }else{
            abort(403, 'Unauthorized action.');
        }

    }

    public function delete(Request $request) {
        
        if($request->ajax()){
            $request->session()->pull('orderVoucher', 'default');

            $status = "success";
            $message = "Voucher successfully deleted";

            return response()->json(array(
                'status' => $status,
                'message'=> $message
            ));

        }else{
            abort(403, 'Unauthorized action.');
        }

    }
}
