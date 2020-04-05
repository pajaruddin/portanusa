<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

use App\Product_package_composition;
use App\Libraries\AppConfiguration;
use App\Libraries\DisplayProduct;

class FilterProductsController extends Controller
{

    function filterStockStatus(Request $request){
      if($request->ajax()){
        $filter = (Session::has('filterProduct') ? Session::get('filterProduct') : "");
        if(empty($filter['stockStatus'])){
          $request->session()->put('filterProduct.stockStatus', 2);
        }else{
          $request->session()->pull('filterProduct.stockStatus', 'default');
        }
        
        $url = $request->input('currentUrl');

        return $this->displayList($url);
      }else{
        abort(403, 'Unauthorized action.');
      }
    }

    function filterStatus(Request $request){
      if($request->ajax()){
        $filter = (Session::has('filterProduct') ? Session::get('filterProduct') : "");
        if(empty($filter['status'])){
          $request->session()->put('filterProduct.status', 'Refurbished');
        }else{
          $request->session()->pull('filterProduct.status', 'default');
        }
        
        $url = $request->input('currentUrl');

        return $this->displayList($url);
      }else{
        abort(403, 'Unauthorized action.');
      }
    }

    function filterSortProduct(Request $request){
      if($request->ajax()){
        $orderBy = $request->input('orderBy');
        $request->session()->put('filterProduct.orderBy', $orderBy);

        $url = $request->input('currentUrl');

        return $this->displayList($url);
      }else{
        abort(403, 'Unauthorized action.');
      }
    }

    function filterPrice(Request $request){
      if($request->ajax()){
        $price = $request->input('price');
        $request->session()->put('filterProduct.price', $price);

        $url = $request->input('currentUrl');

        return $this->displayList($url);
      }else{
        abort(403, 'Unauthorized action.');
      }
    }

    function displayList($url){
      $products = DisplayProduct::getList();
      $products = $products->withPath($url);
      $filter = (Session::has('filterProduct') ? Session::get('filterProduct') : "");

      if(!empty($filter["search"])){
        $products = $products->withPath($url."?search=".$filter['search']);
      }

      $data['products'] = $products;
      $data['filter'] = $filter;
      $data['asset_domain'] = AppConfiguration::assetDomain()->value;
      $data['image_path'] = AppConfiguration::productImagePath()->value;

      $response = view('product.listProduct')->with($data)->render();
      $status = "success";

      return response()->json(array(
        'status' => $status,
        'html' => $response,
      ));
    }

}
