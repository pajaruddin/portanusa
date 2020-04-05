<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Product;
use App\Wishlist;
use App\User;
use App\Product_discussion;

use App\Libraries\AppConfiguration;

use Validator;

class ProductsFormController extends Controller
{

    public function discussion(Request $request) {
    
        if($request->ajax()){
            $attributeNames = array(
                'message' => "Message"
            );
    
            $validator = Validator::make($request->all(), array(
                        'message' => 'required'
            ));
    
            $validator->setAttributeNames($attributeNames);
    
            if (!$validator->fails()) {
    
                $product_id = $request->input('product_id');
                $customer_id = $request->input('customer_id');
                $parent = "";
                $text = $request->input('message');
    
                
    
                $discussion = new Product_discussion();
                $discussion->product_id = $product_id;
                if(!empty($request->input('parent'))){
                    $parent = $request->input('parent');
                    $discussion->parent = $parent;
                }
                $discussion->customer_id = $customer_id;
                $discussion->text = $text;
                if($discussion->save()){
                    $status = "success";
                    $message = "";
                }else{
                    $status = "error";
                    $message = "Data unsuccessfully sent";
                }
    
            }else{
                $status = "error";
                $message = "Data successfully sent";
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

    public function wishlist(Request $request) {
        
        if($request->ajax()){
            $attributeNames = array(
                'productId' => "Product"
            );

            $validator = Validator::make($request->all(), array(
                        'productId' => 'required'
            ));

            $validator->setAttributeNames($attributeNames);

            if (!$validator->fails()) {

                $user = Auth::user();

                $product_id = $request->input('productId');
                $customer_id = $user->id;

                $wishlist = Wishlist::where('product_id',$product_id)->where('customer_id',$customer_id)->first();
                if(!empty($wishlist)){
                    if($wishlist->delete()){
                        $status = "success";
                        $message = "Product removed from your wishlist";
                    }else{
                        $status = "error";
                        $message = "Product cant removed from your wishlist";
                    }
                }else{
                    $wishlist = new Wishlist();
                    $wishlist->product_id = $product_id;
                    $wishlist->customer_id = $customer_id;
                    if($wishlist->save()){
                        $status = "success";
                        $message = "Product added to your wishlist";
                    }else{
                        $status = "error";
                        $message = "Product cant added to your wishlist";
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

            $wishlist = Wishlist::where('customer_id',$user->id)->get();
            
            $count = count($wishlist);

            return response()->json(array(
                'status' => $status,
                'message'=> $message,
                'totalWishlist' => $count
            ));

        }else{
            abort(403, 'Unauthorized action.');
        }

    }


}