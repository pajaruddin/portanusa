<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

use DisplayProductPrice;
use Validator;
use Shipping;
use AppConfiguration;
use Redirect;

use App\Order;
use App\Order_product;
use App\Cart;
use App\Customer_shipping_address;
use App\Bank;

use App\Mail\CustomerOrder;

class CheckoutsController extends Controller
{
    function index(){
        if (!Auth::check()) {
            return Redirect::back()->with("failed_login", "Please login first");
        }

        $user = Auth::user();
        $products_cart = Cart::select('products.id', 'products.name', 'products.weight', 'product_image.image', 'carts.quantity')
                        ->leftJoin('products', 'products.id', "=", "carts.product_id")
                        ->leftJoin('product_image', 'products.id', "=", "product_image.product_id")
                        ->where('product_image.position', 0)
                        ->where('carts.customer_id', $user->id)
                        ->get();

        $total_price = 0;
        $total_weight = 0;
        if(count($products_cart) != 0){
            foreach($products_cart as $product){
                $price = DisplayProductPrice::getPrice($product->id);
                $product_discount = DisplayProductPrice::getDiscount($product->id, $price);

                $price_after_discount = $product_discount['price'];

                $total_price_product = $price_after_discount * $product->quantity;
                $total_price += $total_price_product;
                $total_weight += $product->weight * $product->quantity;
            }
        }

        $data['total_price'] = $total_price;
        $data['total_weight'] = $total_weight;
        $data['user_shipping_address'] = Customer_shipping_address::where('customer_id', $user->id)->orderBy('id', 'desc')->get();
        
        $data['asset_domain'] = AppConfiguration::assetDomain()->value;
        $data['image_path'] = AppConfiguration::productImagePath()->value;
        $data['products_cart'] = $products_cart;

        $voucher = (Session::has('orderVoucher') ? Session::get('orderVoucher') : "");
        $data['voucher'] = $voucher;

        $shipping = (Session::has('orderShipping') ? Session::get('orderShipping') : "");
        $data['shipping'] = $shipping;

        if(!empty($shipping['address'])){
            $address = Customer_shipping_address::where('customer_id', $user->id)->where('id', $shipping['address'])->first();
            $data['address'] = $address;
        }        

        $data['title'] = "Portanusa - Checkout";
        return view('order.checkout')->with($data);

    }

    function formCheckout(Request $request){
        $attributeNames = array(
            'shipping' => 'Shipping address',
            'grand_price' => 'Grand Price',
            'total_price' => 'Total Price',
            'courier_type' => 'Courier Type',
            'shipping_cost' => 'Shipping Cost',
        );
        $validator = Validator::make($request->all(), array(
                    'shipping' => 'required',
                    'grand_price' => 'required',
                    'total_price' => 'required',
                    'courier_type' => 'required',
                    'shipping_cost' => 'required',
        ));
        $validator->setAttributeNames($attributeNames);
        if (!$validator->fails()) {
        
            $status = "";
            $message = "";
            
            $user = Auth::user();

            $next_invoice = 1;

            $last_order = Order::orderBy("id","desc")->first();
            if(!empty($last_order)){
                $last_invoice = substr($last_order->invoice_no, -4);
                $next_invoice = $last_invoice + 1;
            }
            $invoice_no = "PRTNS/".date('Y')."/".date('m')."/";
            if($next_invoice < 10){
                $invoice_no .= "000".$next_invoice;
            }else if($next_invoice < 100){
                $invoice_no .= "00".$next_invoice;
            }else if($next_invoice < 1000){
                $invoice_no .= "0".$next_invoice;
            }else if($next_invoice < 10000){
                $invoice_no .= $next_invoice;
            }
            
            $grand_price = $request->input("grand_price");
            $total_price = $request->input("total_price");
            $courier_type = $request->input("courier_type");
            $shipping_cost = $request->input("shipping_cost");
            $tax_invoice = $request->input("tax_invoice");

            $voucher = (Session::has('orderVoucher') ? Session::get('orderVoucher') : "");
            $discount = 0;
            if(!empty($voucher['discount'])){
                $discount = $voucher["discount"];
            }
            $discount_price = ($discount * $total_price) / 100;

            $shipping = (Session::has('orderShipping') ? Session::get('orderShipping') : "");
            $address = Customer_shipping_address::where('customer_id', $user->id)->where('id', $shipping['address'])->first();
            
            $province = Shipping::get_province($address->province_id);
            $city = Shipping::get_city($address->city_id,$address->province_id);

            $order = new Order();
            $order->customer_id = $user->id;
            $order->invoice_no = $invoice_no;
            $order->shipping_name = $address->receiver_name; 
            $order->shipping_address = $address->address; 
            $order->shipping_province = $province['name']; 
            $order->shipping_city = $city['type']." ".$city['city_name'];
            $order->shipping_postal_code = $address->postal_code; 
            $order->shipping_phone = $address->receiver_phone; 
            $order->shipping_type = $courier_type; 
            $order->shipping_price = $shipping_cost; 
            $order->voucher_code = (!empty($voucher) ? $voucher['code'] : NULL); 
            $order->discount_price = (!empty($voucher) ? ceil($discount_price) : NULL); 
            $order->tax_invoice = (!empty($tax_invoice) ? 'T' : 'F'); 
            $order->total_price = ceil($grand_price); 
            $order->status = 2; 
            $order->created_at = date('Y-m-d H:i:s');
            if($order->save()){
                $products_cart = Cart::select('products.id', 'products.name', 'products.weight', 'product_image.image', 'carts.quantity', 'product_stock_status.name as status', 'products.pre_order_text')
                        ->leftJoin('products', 'products.id', "=", "carts.product_id")
                        ->leftJoin('product_stock_status', 'products.stock_status_id', "=", "product_stock_status.id")
                        ->leftJoin('product_image', 'products.id', "=", "product_image.product_id")
                        ->where('product_image.position', 0)
                        ->where('carts.customer_id', $user->id)
                        ->get();
                if(count($products_cart) != 0){
                    foreach($products_cart as $product){
                        $price = DisplayProductPrice::getPrice($product->id);
                        $product_discount = DisplayProductPrice::getDiscount($product->id, $price);

                        $price_after_discount = $product_discount['price'];

                        $total_price_product = $price_after_discount * $product->quantity;

                        $order_product = new Order_product();
                        $order_product->order_id = $order->id; 
                        $order_product->name = $product->name; 
                        $order_product->quantity = $product->quantity; 
                        $order_product->price = ceil($price_after_discount); 
                        $order_product->product_status = $product->status;
                        $order_product->pre_order_time = ($product->pre_order_text * 7);
                        $order_product->save();
                        
                    }
                }

                $request->session()->pull('orderVoucher', 'default');
                $request->session()->pull('orderShipping', 'default');

                $cart = Cart::where('carts.customer_id', $user->id);
                $cart->delete();

                $order_products = Order_product::where('order_id', $order->id)->get();
                $order = Order::find($order->id);

                Mail::to($user->email)->send(new CustomerOrder($user, $order, $order_products));

                $status = "success";
                $message = "Your purchase is successful, please choose the bank below and make a transfer according to the total payment that we sent to your email";
            }
            return redirect('checkout/payment')->with($status, $message);
        }else{
            $status = "error";
            $message = "";
            $errors = $validator->errors();
            foreach ($errors->all() as $error_message) {
                $message .= $error_message . "<br/>";
            }
            return redirect('checkout/')->with($status, $message);
        }
    }

    function payment(){
        if (!Auth::check()) {
            return redirect('/')->with("failed_login", "Please login first");
        }

        $user = Auth::user();

        $order = Order::where('customer_id', $user->id)->where('transfer_image', NULL)->where('status', 2)->orderBy('id', 'desc')->first();
        if(empty($order)){
            return redirect('account/history-order');
        }
        $order_products = Order_product::where("order_id", $order->id)->get();
        $total_product = 0;
        $total_price = 0;

        foreach($order_products as $product){
            $product_price = $product->price * $product->quantity;
            $total_price += $product_price;
            $total_product += $product->quantity;
        }

        $data['order'] = $order;
        $data['order_products'] = $order_products;
        $data['total_product'] = $total_product;
        $data['total_price'] = $total_price;

        $data['asset_domain'] = AppConfiguration::assetDomain()->value;
        $data['bankImagePath'] = AppConfiguration::BankImagePath()->value;

        $data['banks'] = Bank::get();
        $data['title'] = "Portanusa - Payment";
        return view('order.payment')->with($data);
    }
}
