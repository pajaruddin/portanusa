<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App;
use App\Subject;
use App\Category;
use App\Product;
use App\Sale_event;
use App\Libraries\AppConfiguration;
use App\Libraries\DisplayProduct;

class ProductsController extends Controller
{
    function productRefresh(Request $request, $type, $url){

        $request->session()->pull('filterProduct', 'default');
        $search = $request->input('search');
        $param_search = str_replace("'","",$search);

        if($type == "search"){
            return redirect('/'.$type.'/'.$url.'?search='.$param_search);
        }else{
            return redirect('/'.$type.'/'.$url);
        }

    }

    function productCategory(Request $request, $url){

        $banner_title = "";

        $status_id = 1;
        $request->session()->put('filterProduct.statusProduct', $status_id);

        $get_category = Category::where('url',$url)->first();
        
        if(!empty($get_category)){
            $banner_title = $get_category->name;

            if(!empty($get_category->parent)){
                $parent = Category::find($get_category->parent);
                $data['category_parent'] = $parent;
                
                if(!empty($parent->parent)){
                    $grandparent = Category::find($parent->parent);
                    $data['category_grandparent'] = $grandparent;
                }
            }

            $child =  Category::where('parent', $get_category->id)->where('publish','T')->orderBy('id','desc')->get();
            if(count($child) == 0 && !empty($get_category->parent)){
                $child =  Category::where('parent', $get_category->parent)->where('publish','T')->orderBy('id','desc')->get();
            }
            $data['category_child'] = $child;

            $request->session()->put('filterProduct.category', $get_category->id);

            $filter = (Session::has('filterProduct') ? Session::get('filterProduct') : "");
            $data['filter'] = $filter;
        }else{
            abort(404);
        }

        $products = DisplayProduct::getList();
        $all_products = DisplayProduct::getListbyType("category");

        $today = date('Y-m-d H:i:s');
        $total_pre_order = 0;
        $total_second = 0;
        
        if(!empty($all_products)){
            foreach ($all_products as $product) {
                if($product->stock_status_id == 2 && ($product->date_start_periode <= $today && $product->date_end_periode >= $today)){
                    $total_pre_order += 1;
                }
                if($product->status == "Refurbished"){
                    $total_second += 1;
                }
            }
        }

        $asset_domain = AppConfiguration::assetDomain()->value;
        
        $banner_image = "/images/shop_background.jpg";
        $banner_path = AppConfiguration::categoryBannerPath()->value;
        if(!empty($get_category->banner_image)){
            $banner_image = $asset_domain."/".$banner_path."/".$get_category->banner_image;
        }

        $data['banner_title'] = $banner_title;
        $data['banner_image'] = $banner_image;

        $data['products'] = $products;
        $data['all_products'] = $all_products;

        $data['asset_domain'] = AppConfiguration::assetDomain()->value;
        $data['image_path'] = AppConfiguration::productImagePath()->value;

        $data['total_pre_order'] = $total_pre_order;
        $data['total_second'] = $total_second;

        $data['url_list'] = $url;
        $data['category'] = $get_category;
        $data['title'] = 'Portanusa - Product List ' . $banner_title;
        $data['metaDescription'] = $get_category->meta_description;
        $data['metaKeyword'] = $get_category->meta_keyword;
        return view('product.layoutCategory')->with($data);

    }

    function productSubject(Request $request, $url){

        $banner_title = "";

        $status_id = 1;
        $request->session()->put('filterProduct.statusProduct', $status_id);

        $get_subject = Subject::where('url',$url)->first();
        
        if(!empty($get_subject)){
            $banner_title = $get_subject->name;

            if(!empty($get_subject->parent)){
                $parent = Subject::find($get_subject->parent);
                $data['subject_parent'] = $parent;
            }

            $child =  Subject::where('parent', $get_subject->id)->where('publish','T')->orderBy('id','desc')->get();
            if(count($child) == 0 && !empty($get_subject->parent)){
                $child =  Subject::where('parent', $get_subject->parent)->where('publish','T')->orderBy('id','desc')->get();
            }
            $data['subject_child'] = $child;

            $request->session()->put('filterProduct.subject', $get_subject->id);

            $filter = (Session::has('filterProduct') ? Session::get('filterProduct') : "");
            $data['filter'] = $filter;
        }else{
            abort(404);
        }

        $products = DisplayProduct::getList();
        $all_products = DisplayProduct::getListbyType("subject");

        $today = date('Y-m-d H:i:s');
        $total_pre_order = 0;
        $total_second = 0;
        
        if(!empty($all_products)){
            foreach ($all_products as $product) {
                if($product->stock_status_id == 2 && ($product->date_start_periode <= $today && $product->date_end_periode >= $today)){
                    $total_pre_order += 1;
                }
                if($product->status == "Refurbished"){
                    $total_second += 1;
                }
            }
        }

        $data['banner_title'] = $banner_title;

        $data['products'] = $products;
        $data['all_products'] = $all_products;

        $data['asset_domain'] = AppConfiguration::assetDomain()->value;
        $data['image_path'] = AppConfiguration::productImagePath()->value;

        $data['total_pre_order'] = $total_pre_order;
        $data['total_second'] = $total_second;

        $data['url_list'] = $url;
        $data['subject'] = $get_subject;
        $data['title'] = 'Portanusa - Product List ' . $banner_title;
        $data['metaDescription'] = $get_subject->meta_description;
        $data['metaKeyword'] = $get_subject->meta_keyword;
        return view('product.layoutSubject')->with($data);

    }

    function productEvent(Request $request, $url){
        
        $event = Sale_event::where('url', $url)->first();
        if(empty($event)){
            abort(404);
        }

        $status_id = 1;

        $request->session()->put('filterProduct.statusProduct', $status_id);
        $request->session()->put('filterProduct.event', $event->id);

        $filter = (Session::has('filterProduct') ? Session::get('filterProduct') : "");
        $data['filter'] = $filter;

        $products = DisplayProduct::getList();
        $all_products = DisplayProduct::getListbyType("event");

        $today = date('Y-m-d H:i:s');
        $total_pre_order = 0;
        $total_second = 0;
        
        if(!empty($all_products)){
            foreach ($all_products as $product) {
                if($product->stock_status_id == 2 && ($product->date_start_periode <= $today && $product->date_end_periode >= $today)){
                    $total_pre_order += 1;
                }
                if($product->status == "Refurbished"){
                    $total_second += 1;
                }
            }
        }

        $asset_domain = AppConfiguration::assetDomain()->value;

        $banner_title = $event->name;
        
        $banner_image = "/images/shop_background.jpg";
        $banner_path = AppConfiguration::eventBannerPath()->value;
        if(!empty($event->banner_image)){
            $banner_image = $asset_domain."/".$banner_path."/".$event->banner_image;
        }

        $data['banner_title'] = $banner_title;
        $data['banner_image'] = $banner_image;

        $data['products'] = $products;
        $data['all_products'] = $all_products;

        $data['asset_domain'] = $asset_domain;
        $data['image_path'] = AppConfiguration::productImagePath()->value;

        $data['total_pre_order'] = $total_pre_order;
        $data['total_second'] = $total_second;

        $data['url_list'] = $url;
        $data['event'] = $event;
        $data['title'] = 'Portanusa - Product List ' . $banner_title;
        $data['metaDescription'] = $event->meta_description;
        $data['metaKeyword'] = $event->meta_keyword;
        return view('product.layoutEvent')->with($data);
    }

    function productStatus(Request $request, $url){

        if($url == "featured"){
            $banner_title = "Featured";
        }else if($url == "on_sale"){
            $banner_title = "On Sale";
        }else if($url == "best_rated"){
            $banner_title = "Best Rated";
        }else{
            abort(404);
        }

        $status_id = 1;

        $request->session()->put('filterProduct.statusProduct', $status_id);
        $request->session()->put('filterProduct.status_promo', $banner_title);

        $filter = (Session::has('filterProduct') ? Session::get('filterProduct') : "");
        $data['filter'] = $filter;

        $products = DisplayProduct::getList();
        $all_products = DisplayProduct::getListbyType("status_promo");

        $data['banner_title'] = $banner_title;

        $data['products'] = $products;
        $data['all_products'] = $all_products;

        $data['asset_domain'] = AppConfiguration::assetDomain()->value;
        $data['image_path'] = AppConfiguration::productImagePath()->value;

        $data['url_list'] = $url;
        $data['title'] = 'Portanusa - Product List ' . $banner_title;
        return view('product.layoutStatus')->with($data);
    }

    function productSearch(Request $request){
        $status_id = 1;
        $search = $request->input('search');

        $request->session()->put('filterProduct.statusProduct', $status_id);
        $request->session()->put('filterProduct.search', $search);

        $filter = (Session::has('filterProduct') ? Session::get('filterProduct') : "");
        $data['filter'] = $filter;

        $products = DisplayProduct::getList();
        $all_products = DisplayProduct::getListbyType("search");

        $today = date('Y-m-d H:i:s');
        $total_pre_order = 0;
        $total_second = 0;
        
        if(!empty($all_products)){
            foreach ($all_products as $product) {
                if($product->stock_status_id == 2 && ($product->date_start_periode <= $today && $product->date_end_periode >= $today)){
                    $total_pre_order += 1;
                }
                if($product->status == "Refurbished"){
                    $total_second += 1;
                }
            }
        }

        $banner_title = "Search Result '". $search ."'";
        $data['banner_title'] = $banner_title;

        $data['products'] = $products;
        $data['all_products'] = $all_products;

        $data['asset_domain'] = AppConfiguration::assetDomain()->value;
        $data['image_path'] = AppConfiguration::productImagePath()->value;

        $data['total_pre_order'] = $total_pre_order;
        $data['total_second'] = $total_second;
        
        $data['title'] = 'Portanusa - Product ' . $banner_title;
        return view('product.layoutSearch')->with($data);
    }
}
