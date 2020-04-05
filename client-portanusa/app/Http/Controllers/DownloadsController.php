<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use AppConfiguration;

use App\Product_catalog;

class DownloadsController extends Controller
{
    function index(){
        $catalogs = Product_catalog::where("publish", "T")->orderBy("id", "desc")->paginate(16);

        $data['asset_domain'] = AppConfiguration::assetDomain()->value;
        $data['thumb_path'] = AppConfiguration::DownloadThumbImagePath()->value;

        $data['catalogs'] = $catalogs;
        $data['title'] = "Portanusa - Product Catalogue";
        return view('download.page')->with($data);
    }

    function page($url){
        $catalog = Product_catalog::where("url", $url)->first();
        if(empty($catalog)){
            abort(404);
        }

        $data['catalog'] = $catalog;
        $data['title'] = "Portanusa - Product Catalogue ".$catalog->title;
        return view('download.detail')->with($data);
    }
}
