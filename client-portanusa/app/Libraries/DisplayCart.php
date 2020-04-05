<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Auth;
use App\Cart;

class DisplayCart {

    public static function countCart() {
        $count = 0;
        if (Auth::check()) {
            $cart = Cart::selectRaw('SUM(carts.quantity) as total_qty')
                    ->where('customer_id',Auth::id())
                    ->groupBy('customer_id')
                    ->first();
            if(!empty($cart)){
                $count = $cart->total_qty;
            }            
        }
        return $count;
    }

}
