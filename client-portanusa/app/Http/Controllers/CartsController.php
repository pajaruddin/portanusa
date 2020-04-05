<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Cart;

use AppConfiguration;
use Validator;
use Redirect;

class CartsController extends Controller
{
    function index(Request $request){
        if (!Auth::check()) {
            return Redirect::back()->with("failed_login", "Please login first");
        }

        $request->session()->pull('orderShipping', 'default');

        $user = Auth::user();
        $products_cart = Cart::select('products.id', 'products.name', 'product_image.image', 'carts.quantity')
                        ->leftJoin('products', 'products.id', "=", "carts.product_id")
                        ->leftJoin('product_image', 'products.id', "=", "product_image.product_id")
                        ->where('product_image.position', 0)
                        ->where('carts.customer_id', $user->id)
                        ->get();

        $voucher = (Session::has('orderVoucher') ? Session::get('orderVoucher') : "");
        $data['voucher'] = $voucher;

        $data['asset_domain'] = AppConfiguration::assetDomain()->value;
        $data['image_path'] = AppConfiguration::productImagePath()->value;
        $data['products_cart'] = $products_cart;
        $data['title'] = "Portanusa - Cart";
        return view('order.cart')->with($data);
    }

    public function add(Request $request) {
        
        if($request->ajax()){
            $attributeNames = array(
                'product_id' => "Product"
            );

            $validator = Validator::make($request->all(), array(
                        'product_id' => 'required'
            ));

            $validator->setAttributeNames($attributeNames);

            $status = "";
            $message = "";

            if (!$validator->fails()) {

                $user = Auth::user();

                $product_id = $request->input('product_id');
                $customer_id = $user->id;

                $cart = Cart::where('product_id',$product_id)->where('customer_id',$customer_id)->first();
                if(!empty($cart)){
                    $cart->quantity += 1;
                    $cart->save();
                }else{
                    $cart = new Cart();
                    $cart->product_id = $product_id;
                    $cart->customer_id = $customer_id;
                    $cart->quantity = 1;
                    $cart->save();
                }

            }else{
                $status = "error";
                $message = "";
                $errors = $validator->errors();
                foreach ($errors->all() as $error_message) {
                    $message .= $error_message . "<br/>";
                }

            }

            $cart = Cart::selectRaw('SUM(carts.quantity) as total_qty')
                    ->where('customer_id',$user->id)
                    ->groupBy('customer_id')
                    ->first();

            return response()->json(array(
                'status' => $status,
                'message'=> $message,
                'totalCart' => $cart->total_qty
            ));

        }else{
            abort(403, 'Unauthorized action.');
        }

    }

    public function delete(Request $request) {
        
        if($request->ajax()){
            $attributeNames = array(
                'product_id' => "Product"
            );

            $validator = Validator::make($request->all(), array(
                        'product_id' => 'required'
            ));

            $validator->setAttributeNames($attributeNames);

            $status = "";
            $message = "";

            if (!$validator->fails()) {

                $user = Auth::user();

                $product_id = $request->input('product_id');
                $customer_id = $user->id;

                $cart = Cart::where('product_id',$product_id)->where('customer_id',$customer_id)->first();
                if($cart->delete()){
                    $status = "success";
                    $message = "Product successfully deleted";
                }else{
                    $status = "error";
                    $message = "Product unsuccessfully deleted";
                }

            }else{
                $status = "error";
                $message = "";
                $errors = $validator->errors();
                foreach ($errors->all() as $error_message) {
                    $message .= $error_message . "<br/>";
                }

            }

            $cart = Cart::selectRaw('SUM(carts.quantity) as total_qty')
                    ->where('customer_id',$user->id)
                    ->groupBy('customer_id')
                    ->first();

            return response()->json(array(
                'status' => $status,
                'message'=> $message,
                'totalCart' => $cart->total_qty
            ));

        }else{
            abort(403, 'Unauthorized action.');
        }

    }

    public function update(Request $request) {
        
        if($request->ajax()){
            $attributeNames = array(
                'product_id' => "Product",
                'quantity' => "Quantity"
            );

            $validator = Validator::make($request->all(), array(
                        'product_id' => 'required',
                        'quantity' => 'required'
            ));

            $validator->setAttributeNames($attributeNames);

            $status = "";
            $message = "";

            if (!$validator->fails()) {

                $user = Auth::user();

                $product_id = $request->input('product_id');
                $quantity = $request->input('quantity');
                $customer_id = $user->id;

                $cart = Cart::where('product_id',$product_id)->where('customer_id',$customer_id)->first();
                if(!empty($cart)){
                    $cart->quantity = $quantity;
                    if($cart->save()){
                        $status = "success";
                        $message = "Product successfully updated";
                    }else{
                        $status = "error";
                        $message = "Product unsuccessfully updated";
                    }
                }

            }else{
                $status = "error";
                $message = "";
                $errors = $validator->errors();
                foreach ($errors->all() as $error_message) {
                    $message .= $error_message . "<br/>";
                }

            }

            $cart = Cart::selectRaw('SUM(carts.quantity) as total_qty')
                    ->where('customer_id',$user->id)
                    ->groupBy('customer_id')
                    ->first();

            return response()->json(array(
                'status' => $status,
                'message'=> $message,
                'totalCart' => $cart->total_qty
            ));

        }else{
            abort(403, 'Unauthorized action.');
        }

    }
}
