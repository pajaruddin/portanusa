<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Auth;
use App\Wishlist;

class DisplayWishlist {

    public static function checkProduct($product_id) {
        $status = FALSE;
        if (Auth::check()) {
            $user = Auth::user();
            $wishlist = Wishlist::where('product_id',$product_id)->where('customer_id',$user->id)->first();
            if(!empty($wishlist)){
                $status = TRUE;
            }
        }
        return $status;
    }

    public static function countWishlist() {
        $count = 0;
        if (Auth::check()) {
            $user = Auth::user();
            $wishlist = Wishlist::where('customer_id',$user->id)->get();
            
            $count = count($wishlist);
            
        }
        return $count;
    }

}
