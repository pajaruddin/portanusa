<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use AppConfiguration;
use App\Catalog;

class CataloguesController extends Controller
{
    function index(Request $request){

        $data['catalogues'] = Catalog::orderBy('id', 'desc')->get();
        $data['file_path'] = AppConfiguration::catalogueFilePath()->value;
        $data['asset_domain'] = AppConfiguration::assetDomain()->value;
        $data['title'] = "Porta Nusa - E Catalogue";
        return view('catalogue.page')->with($data);
    }
}
