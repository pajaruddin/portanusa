<?php

namespace App\Libraries;

use App\Product;
use App\Product_package_composition;
use App\Sale_event;
use App\Sale_event_product;
use Auth;
use Session;

class DisplayProductPrice {

  public static function getPrice($id){
    $product = Product::find($id);

    $price = $product->price;
    if($product->type_status_id != 1){
      $price = 0;
      $product_composition  = Product_package_composition::select('products.price', 'products.id', 'products.type_status_id')
                          ->leftJoin('products', 'product_package_composition.product_id', '=', 'products.id')
                          ->where('package_id',$product->id)
                          ->get();
      if(!empty($product_composition)){
        foreach ($product_composition as $package) {
          $price += $package->price;
        }
      }
    }

    return $price;
  }

  public static function getDiscount($id, $price){
    $product = Product::find($id);
  
    $event_name = "";
    
    $discount = (!empty($product->discount) ? $product->discount : 0);

    $today = date('Y-m-d H:i:s');

    $sale_event_product = Sale_event_product::where('product_id', $product->id)->orderBy('id', 'desc')->first();
    if(!empty($sale_event_product)){
      $event = Sale_event::find($sale_event_product->sale_event_id);
      if(!empty($event)){
        if($event->enable == "T"){
          if(($event->date_start <= $today && $event->date_end >= $today)){
            $discount = (!empty($sale_event_product->discount) ? $sale_event_product->discount : $discount);
            $event_name = $event->name;
          }
        }
      }
    }

    $product_discount = ($price * $discount) / 100;
    $price_after_discount = $price - $product_discount;

    $data['price'] = $price_after_discount;
    $data['discount'] = $discount;
    $data['event'] = $event_name;

    return $data;
  }

}
