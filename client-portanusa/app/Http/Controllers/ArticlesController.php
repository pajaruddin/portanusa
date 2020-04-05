<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use AppConfiguration;

use App\Article;
use App\Article_category;
use App\Article_product;

class ArticlesController extends Controller
{
    function index($url){
        $article_category = Article_category::where("url", $url)->first();
        if(empty($article_category)){
            abort(404);
        }
        
        $articles = Article::where("publish", "T")->where("category_id", $article_category->id)->orderBy("id", "desc")->paginate(16);

        $data['asset_domain'] = AppConfiguration::assetDomain()->value;
        $data['banner_path'] = AppConfiguration::AricleBannerImagePath()->value;

        $data['articles'] = $articles;
        $data['article_category'] = $article_category;
        $data['title'] = "Portanusa - ". $article_category->name;
        return view('article.page')->with($data);
    }

    function page($url, $articleUrl){
        $article_category = Article_category::where("url", $url)->first();
        if(empty($article_category)){
            abort(404);
        }

        $article = Article::where("url", $articleUrl)->where("category_id", $article_category->id)->first();
        if(empty($article)){
            abort(404);
        }

        $article_products = Article_product::select('products.id', 'products.name', 'code', 'stock_status_id', 'product_stock_status.name as stock_status', 'products.date_start_periode', 'products.date_end_periode', 'products.url as url', 'product_image.image as image', 'able_to_order', 'products.type_status_id')->
                            leftJoin('products', 'article_products.product_id', '=', 'products.id')->
                            leftJoin('product_image', 'product_image.product_id', '=', 'products.id')->
                            leftJoin('product_stock_status', 'product_stock_status.id', '=', 'products.stock_status_id')->
                            where('product_image.position', 0)->
                            where('products.type_status_id', 1)->
                            where("article_id", $article->id)->
                            where('products.publish','T')->where('deleted','F')->
                            orderBy('products.created_at','desc')->get();

        $data['asset_domain'] = AppConfiguration::assetDomain()->value;
        $data['banner_path'] = AppConfiguration::AricleBannerImagePath()->value;
        $data['image_path'] = AppConfiguration::productImagePath()->value;

        $data['article'] = $article;
        $data['article_category'] = $article_category;
        $data['article_products'] = $article_products;
        $data['title'] = "Portanusa - ". $article_category->name." ".$article->title;
        return view('article.detail')->with($data);
    }
}
