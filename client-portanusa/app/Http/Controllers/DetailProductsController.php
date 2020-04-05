<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Category;
use App\Product;
use App\Product_image;
use App\Product_package_item;
use App\Product_package_composition;
use App\Product_related;
use App\Product_discussion;
use App\Product_recent_view;

use App\Libraries\AppConfiguration;

class DetailProductsController extends Controller
{
    function detailBase(Request $request, $url){
        $request->session()->pull('filterProduct', 'default');

        $product = Product::where('url',$url)->first();
        if(empty($product)){
            abort(404);
        }

        $ip_client = \Request::ip();
        $get_recent_product = Product_recent_view::where('product_id',$product->id)->where('ip',$ip_client)->first();
        if(!empty($get_recent_product)){
            $get_recent_product->created_at = date('Y-m-d H:i:s');
            $get_recent_product->save();
        }else{
            $recent_product = new Product_recent_view();
            $recent_product->ip = $ip_client;
            $recent_product->product_id = $product->id;
            $recent_product->created_at = date('Y-m-d H:i:s');
            $recent_product->save();
        }

        $product_image_base = Product_image::where('product_id',$product->id)->where('position',0)->first();
        $product_images = Product_image::where('product_id',$product->id)->orderBy('position','asc')->get();

        $category = Category::find($product->category_id);

        $products_related = Product_related::select('products.id', 'products.name', 'code', 'stock_status_id', 'product_stock_status.name as stock_status', 'products.date_start_periode', 'products.date_end_periode', 'products.url as url', 'product_image.image as image', 'able_to_order', 'products.type_status_id')->
                            leftJoin('products', 'product_related.related_id', '=', 'products.id')->
                            leftJoin('product_image', 'product_image.product_id', '=', 'products.id')->
                            leftJoin('product_stock_status', 'product_stock_status.id', '=', 'products.stock_status_id')->
                            where('product_image.position', 0)->
                            where('products.type_status_id', 1)->
                            where('product_related.product_id', $product->id)->
                            where('products.publish','T')->where('deleted','F')->
                            orderBy('products.created_at','desc')->skip(0)->take(4)->get();
        $data['product_related'] = $products_related;

        $products_recent = Product_recent_view::select('products.id', 'products.name', 'code', 'stock_status_id', 'product_stock_status.name as stock_status', 'products.date_start_periode', 'products.date_end_periode', 'products.url as url', 'product_image.image as image', 'able_to_order', 'products.type_status_id')->
                            leftJoin('products', 'product_recent_views.product_id', '=', 'products.id')->
                            leftJoin('product_image', 'product_image.product_id', '=', 'products.id')->
                            leftJoin('product_stock_status', 'product_stock_status.id', '=', 'products.stock_status_id')->
                            where('product_image.position', 0)->
                            where('products.type_status_id', 1)->
                            where('product_recent_views.product_id', '!=', $product->id)->
                            where('product_recent_views.ip', $ip_client)->
                            where('products.publish','T')->where('deleted','F')->
                            orderBy('product_recent_views.created_at','desc')->skip(0)->take(4)->get();
        $data['products_recent'] = $products_recent;

        $today = date('Y-m-d H:i:s');
        //get product package item
        $product_package_item = Product_package_item::select('products.url', 'product_package_item.label')
                                ->leftJoin('products', 'product_package_item.package_id', '=', 'products.id')
                                ->where('product_package_item.product_id', $product->id)
                                ->whereRaw("(products.date_start_periode IS NULL OR products.date_start_periode <= '".$today."')")
                                ->whereRaw("(products.date_end_periode IS NULL OR products.date_end_periode >= '".$today."')")
                                ->get();
        $data['package_items'] = $product_package_item;

        //discussion product
        $product_discussions_parent = Product_discussion::select("product_discussions.id", "product_discussions.customer_id","product_discussions.user_id", "product_discussions.text", "product_discussions.created_at", "customers.first_name", "customers.last_name", "users.first_name as user_first_name", "users.last_name as user_last_name")->
                                        leftJoin('customers', 'customers.id', '=', 'product_discussions.customer_id')->
                                        leftJoin('users', 'users.id', '=', 'product_discussions.user_id')->
                                        whereRaw('parent IS NULL')->
                                        where('product_id', $product->id)->
                                        where('product_discussions.publish', 'T')->
                                        orderBy('product_discussions.id','desc')->get();
        $product_discussions_child = array();
        if(count($product_discussions_parent) != 0){
            foreach($product_discussions_parent as $discussion_parent){
            $product_discussions_child[$discussion_parent->id] = Product_discussion::select("product_discussions.customer_id","product_discussions.user_id", "product_discussions.text", "product_discussions.created_at", "customers.first_name", "customers.last_name", "users.first_name as user_first_name", "users.last_name as user_last_name")->
                                                                    leftJoin('customers', 'customers.id', '=', 'product_discussions.customer_id')->
                                                                    leftJoin('users', 'users.id', '=', 'product_discussions.user_id')->
                                                                    where('product_discussions.parent', $discussion_parent->id)->
                                                                    where('product_id', $product->id)->
                                                                    where('product_discussions.publish', 'T')->
                                                                    orderBy('product_discussions.id','desc')->get();
            }
        }
        $data['product_discussions_parent'] = $product_discussions_parent;
        $data['product_discussions_child'] = $product_discussions_child;

        $data['product'] = $product;
        $data['product_images'] = $product_images;
        $data['product_image_base'] = $product_image_base;
        $data['category'] = $category;

        $data['asset_domain'] = AppConfiguration::assetDomain()->value;
        $data['image_path'] = AppConfiguration::productImagePath()->value;

        $data['title'] = 'Portanusa - ' . $product->name;
        $data['metaDescription'] = $product->meta_description;
        $data['metaKeyword'] = $product->meta_keyword;
        return view('product.detailBase')->with($data);
    }

    function detailPackage(Request $request, $url){
        $request->session()->pull('filterProduct', 'default');

        $product = Product::where('url',$url)->first();
        if(empty($product)){
            abort(404);
        }
        $product_image_base = Product_image::where('product_id',$product->id)->where('position',0)->first();
        $product_images = Product_image::where('product_id',$product->id)->orderBy('position','asc')->get();

        $products_package_compositions = Product_package_composition::select('products.id', 'products.name', 'code', 'stock_status_id', 'product_stock_status.name as stock_status', 'products.date_start_periode', 'products.date_end_periode', 'products.url as url', 'product_image.image as image', 'able_to_order', 'products.type_status_id')->
                            leftJoin('products', 'product_package_composition.product_id', '=', 'products.id')->
                            leftJoin('product_image', 'product_image.product_id', '=', 'products.id')->
                            leftJoin('product_stock_status', 'product_stock_status.id', '=', 'products.stock_status_id')->
                            where('product_image.position', 0)->
                            where('products.type_status_id', 1)->
                            where('product_package_composition.package_id', $product->id)->
                            where('products.publish','T')->where('deleted','F')->
                            orderBy('products.created_at','desc')->skip(0)->take(4)->get();
        $data['products_package_compositions'] = $products_package_compositions;

        $today = date('Y-m-d H:i:s');
        //get product package item
        $base = Product_package_item::where('package_id', $product->id)->first();
        $product_base = Product::find($base->product_id);
        $product_package_item = Product_package_item::select('products.url', 'product_package_item.label')
                                ->leftJoin('products', 'product_package_item.package_id', '=', 'products.id')
                                ->where('product_package_item.product_id', $product_base->id)
                                ->whereRaw("(products.date_start_periode IS NULL OR products.date_start_periode <= '".$today."')")
                                ->whereRaw("(products.date_end_periode IS NULL OR products.date_end_periode >= '".$today."')")
                                ->get();
        $data['package_items'] = $product_package_item;
        $data['product_base'] = $product_base;

        //discussion product
        $product_discussions_parent = Product_discussion::select("product_discussions.id", "product_discussions.customer_id","product_discussions.user_id", "product_discussions.text", "product_discussions.created_at", "customers.first_name", "customers.last_name", "users.first_name as user_first_name", "users.last_name as user_last_name")->
                                        leftJoin('customers', 'customers.id', '=', 'product_discussions.customer_id')->
                                        leftJoin('users', 'users.id', '=', 'product_discussions.user_id')->
                                        whereRaw('parent IS NULL')->
                                        where('product_id', $product->id)->
                                        where('product_discussions.publish', 'T')->
                                        orderBy('product_discussions.id','desc')->get();
        $product_discussions_child = array();
        if(count($product_discussions_parent) != 0){
            foreach($product_discussions_parent as $discussion_parent){
            $product_discussions_child[$discussion_parent->id] = Product_discussion::select("product_discussions.customer_id","product_discussions.user_id", "product_discussions.text", "product_discussions.created_at", "customers.first_name", "customers.last_name", "users.first_name as user_first_name", "users.last_name as user_last_name")->
                                                                    leftJoin('customers', 'customers.id', '=', 'product_discussions.customer_id')->
                                                                    leftJoin('users', 'users.id', '=', 'product_discussions.user_id')->
                                                                    where('product_discussions.parent', $discussion_parent->id)->
                                                                    where('product_id', $product->id)->
                                                                    where('product_discussions.publish', 'T')->
                                                                    orderBy('product_discussions.id','desc')->get();
            }
        }
        $data['product_discussions_parent'] = $product_discussions_parent;
        $data['product_discussions_child'] = $product_discussions_child;

        $data['product'] = $product;
        $data['product_images'] = $product_images;
        $data['product_image_base'] = $product_image_base;

        $data['asset_domain'] = AppConfiguration::assetDomain()->value;
        $data['image_path'] = AppConfiguration::productImagePath()->value;

        $data['title'] = 'Portanusa - ' . $product->name;
        $data['metaDescription'] = $product->meta_description;
        $data['metaKeyword'] = $product->meta_keyword;
        return view('product.detailPackage')->with($data);
    }
}
