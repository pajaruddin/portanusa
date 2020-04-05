<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

use App\Libraries\Shipping;

use App\Customer_shipping_address;

class ShippingAddressController extends Controller
{
    function getCities(Request $request) {
        if($request->ajax()){
            $province_id = $request->input('province_id');
            $cities = Shipping::getCities($province_id);
        
            $data_cities = array();
            if (!empty($cities)) {
                if ($cities['rajaongkir']['status']['code'] == 200) {
                    foreach ($cities['rajaongkir']['results'] as $data) {
                        $data_cities[] = array(
                            'id' => $data["city_id"],
                            'type' => $data['type'],
                            'name' => $data["city_name"]
                        );
                    }
                    $status = 'success';
                    $message = "";
                } else {
                    $status = 'error';
                    $message = $cities['rajaongkir']['status']['description'];
                }
            } else {
                $status = 'error';
                $message = 'Kota/Kabupaten tidak ditemukan';
            }
        
            return response()->json(array(
                'status' => $status,
                'data' => $data_cities,
                'message'=> $message
            ));
        }else{
            abort(403, 'Unauthorized action.');
        }
    }

    function getCost(Request $request){
        if($request->ajax()){
            $id = $request->input('shipping');
            $weight = $request->input('weight');
            $user = Auth::user();

            $address = Customer_shipping_address::where('customer_id', $user->id)->where('id', $id)->first();
            $cost = Shipping::getCost($address->city_id, $weight);

            $costs = array();
            if ($cost['rajaongkir']['status']['code'] == 200) {
                foreach ($cost['rajaongkir']['results'][0]['costs'] as $data) {
                    $costs[] = array(
                        'service' => $data["service"],
                        'etd' => $data['cost'][0]["etd"],
                        'value' => $data["cost"][0]["value"]
                    );
                }

                $request->session()->put('orderShipping.address', $address->id);
                $request->session()->put('orderShipping.couriers', $costs);

                $status = 'success';
                $message = "";
            } else {
                $status = 'error';
                $message = $cost['rajaongkir']['status']['description'];
            }

            

            return response()->json(array(
                'status' => $status,
                'message'=> $message
            ));
        }else{
            abort(403, 'Unauthorized action.');
        }
    }
}
