<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use AppConfiguration;
use App\Libraries\DisplayProduct;
use App\Voucher;
use App\Sale_event;
use App\Category;
use App\Category_product;
use App\Banner;
use App\Service;

class HomepagesController extends Controller
{
    function index(Request $request)
    {
        $request->session()->pull('filterProduct', 'default');

        $data['asset_domain'] = AppConfiguration::assetDomain()->value;
        $data['VoucherImagePath'] = AppConfiguration::VoucherImagePath()->value;
        $today = date('Y-m-d H:i:s');

        $data['vouchers'] = Voucher::where('date_start', '<=', $today)->where('date_end', '>=', $today)->orderBy('id', 'desc')->skip(0)->take(4)->get();

        $data['banners'] = Banner::where('date_start', '<=', $today)->where('date_end', '>=', $today)->where('publish', 'T')->orderBy('id', 'desc')->get();
        $data['bannerPath'] = AppConfiguration::bannerImagePath()->value;

        $data['services'] = Service::get();
        $data['servicePath'] = AppConfiguration::serviceImagePath()->value;

        $event = Sale_event::where('date_start', '<=', $today)->where('date_end', '>=', $today)->where('enable', 'T')->orderBy('id', 'desc')->first();
        if (!empty($event)) {
            $data['event'] =  $event;
            $status_id = 1;
            $paginate = 4;

            $request->session()->put('filterProduct.statusProduct', $status_id);
            $request->session()->put('filterProduct.paginate', $paginate);
            $request->session()->put('filterProduct.event', $event->id);
            $sale_products = DisplayProduct::getList();
            $data['sale_products'] = $sale_products;
        }

        $request->session()->pull('filterProduct.event', 'default');

        $status_id = 1;
        $paginate = 8;
        $request->session()->put('filterProduct.paginate', $paginate);
        $request->session()->put('filterProduct.statusProduct', $status_id);

        $categories = Category::where('display_home', 'T')->get();
        $products_category = array();
        $category = array();
        foreach ($categories as $category_id) {
            $category[$category_id->id] = Category::find($category_id->id);
            $request->session()->put('filterProduct.category', $category_id->id);
            $products_category[$category_id->id] = DisplayProduct::getList();
        }
        $data['categories'] = $categories;
        $data['category'] = $category;
        $data['products_category'] = $products_category;

        $request->session()->pull('filterProduct.category', 'default');

        $paginate = 8;
        $request->session()->put('filterProduct.paginate', $paginate);

        $status_arr = ["Featured", "On Sale", "Best Rated"];
        $products_status = [];
        foreach ($status_arr as $status) {
            $request->session()->put('filterProduct.status_promo', $status);
            $products_status[$status] = DisplayProduct::getList();
        }

        $data['status_arr'] = $status_arr;
        $data['products_status'] = $products_status;

        $highlight_categories = Category::where('highlight', 'T')->where('publish', 'T')->get();
        $highlight_products = array();
        if (!empty($highlight_categories) && count($highlight_categories)) {
            foreach ($highlight_categories as $highlight) {
                $highlight_products[$highlight->id] = Category_product::select('products.id', 'products.name', 'products.url', 'product_image.image')
                    ->leftJoin('products', 'products.id', '=', 'category_products.product_id')
                    ->leftJoin('product_image', 'product_image.product_id', '=', 'products.id')
                    ->where('category_products.category_id', $highlight->id)
                    ->where('product_image.position', 0)
                    ->limit(4)
                    ->get();
            }
        }

        $data['highlight_categories'] = $highlight_categories;
        $data['highlight_products'] = $highlight_products;

        $data['image_path'] = AppConfiguration::productImagePath()->value;
        $request->session()->pull('filterProduct', 'default');
        $data['title'] = "Toko Network Paling Lengkap – Cisco, Juniper, Fortinet, Patch cord dan Fiber !!";
        return view('homepage.page')->with($data);
    }

    function pagePhp()
    {
        return view('layouts.pagePhp');
    }
}
