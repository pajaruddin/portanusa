<?php

namespace App\Libraries;

use App\Product;

class DisplayProductCart {

  public static function getStatus($id, $price){
    $product = Product::find($id);

    $label = "";
    $button = "";
    $add_to_cart = FALSE;
    $label_cart = "";
    $label_status = "";
    $today = date('Y-m-d H:i:s');

    if($price == 0){
      $label = "Price not yet available";
      if($product->stock_status_id == 2){
        if($product->date_start_periode <= $today && $product->date_end_periode >= $today){
          $label_status = "Pre Order";
        }
      }else if($product->stock_status_id == 3){
        $label_status = "Out of stock";
      }
    }else{
      if($product->stock_status_id == 1){
        if($product->able_to_order == "T"){
          $add_to_cart = TRUE;
          $label_cart = "Add to Cart";
        }
      }else if($product->stock_status_id == 2){
        if($product->date_start_periode <= $today && $product->date_end_periode >= $today){
          if($product->able_to_order == "T"){
            $add_to_cart = TRUE;
            $label_cart = "Pre Order";
          }
          $label_status = "Pre Order";
        }else{
          if($product->able_to_order == "T"){
            $add_to_cart = TRUE;
            $label_cart = "Add to Cart";
          }
        }
      }else{
        $label_status = "Out of stock";
      }
    }

    $data['label'] = $label;
    $data['button'] = $button;
    $data['add_to_cart'] = $add_to_cart;
    $data['label_cart'] = $label_cart;
    $data['label_status'] = $label_status;

    return $data;

  }

}