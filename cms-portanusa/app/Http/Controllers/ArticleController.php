<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Article;
use App\Article_category;
use App\Article_product;
use App\Header;

use App\Product;
use App\Product_image;

use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ArticleController extends Controller {

    function searchProduct(Request $request) {
        if ($request->ajax()) {
            $keyword = $request->input('q');
            
            $limit = 10;
            $products = Product::from('products AS product')
            ->select('product.id', 'product.name', 'product.code', 'product.price', 'type_status.name AS type_status', 'stock_status.name AS stock_status', 'brand.name AS brand', 'subject.name AS subject', 'image.image AS image')
            ->leftJoin('brands AS brand', 'brand.id', '=', 'product.brand_id')
            ->leftJoin('subjects AS subject', 'subject.id', '=', 'product.subject_id')
            ->leftJoin('product_type_status AS type_status', 'type_status.id', '=', 'product.type_status_id')
            ->leftJoin('product_stock_status AS stock_status', 'stock_status.id', '=', 'product.stock_status_id')
            // ->join($database_master . '.product_availability_status AS availability_status', 'availability_status.id', '=', 'product.availability_status_id')
            ->leftJoin('product_image AS image', 'image.product_id', '=', 'product.id')
            ->where(function ($query) use ($keyword) {
                $query->where('product.name', 'like', '%' . $keyword . '%')
                ->orWhere('product.code', 'like', '%' . $keyword . '%')
                ->orWhere('brand.name', 'like', '%' . $keyword . '%');
            })
            ->where('image.position', 0)
            ->where('product.type_status_id', 1)
            ->where('product.deleted', 'F')
            ->limit($limit)
            ->get();
            
            $total_items = count($products);
            
            $data = array();
            if (!empty($products)) {
                foreach ($products as $product) {
                    $data[] = array(
                        'id' => $product->id,
                        'full_name' => $product->name . " " . $product->code,
                        'brand' => $product->brand,
                        'subject' => $product->subject,
                        'price' => $product->price,
                        'type_status' => $product->type_status,
                        'stock_status' => $product->stock_status,
                        'availability_status' => $product->availability_status,
                        'image' => AppConfiguration::assetPortalDomain()->value . "/" . AppConfiguration::productImagePath()->value . '/' . $product->image
                    );
                }
            }
            $response['total_counts'] = $total_items;
            $response['items'] = $data;
            
            return response()->json($response);
        } else {
            abort(403);
        }
    }

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        return view('article.list')->with($data);
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $articles = Article::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'articles.id', 'article_categories.name as category', 'articles.title', 'articles.created_at', 'articles.publish')
                ->leftJoin('article_categories','article_categories.id','=','articles.category_id')
                ->orderBy('id', 'desc')
                ->get();

        return Datatables::of($articles)
                ->editColumn('active', function ($article) {
                    if ($article->publish == 'T') {
                        $active_checked = 'checked';
                    } else {
                        $active_checked = '';
                    }
                    $active = '<input type="checkbox" name="active" class="make-switch" data-id="' . $article->id . '" data-size="mini" ' . $active_checked . '>';
                    
                    return $active;
                })
                ->addColumn('action', function ($article) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/edit-article", $article->id) . '" class="btn btn-circle btn-icon-only btn-warning" title="Edit Artikel"><i class="fa fa-pencil"></i></a>';
                    $action_button .= '<a href="#" id="delete" data-id="' . $article->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Artikel"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['active', 'action'])
                ->make(true);
    }

    function updateActive(Request $request) {
        if ($request->ajax()) {
            $article = Article::find($request->id);
            $active = $request->input('active');
            
            $article->publish = $active;
            
            if ($article->save()) {

                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_save', ['menu' => 'Artikel'])
                ));
            }
        }
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $article = Article::find($request->id);

            if ($article->delete()) {
                $asset_request = new AssetRequest;
                if ($article->banner_image != NULL) {
                    $destination_path = AppConfiguration::articleBannerPath()->value;
                    $asset_request->delete($destination_path, $article->banner_image);
                }

                Article_product::where('article_id', $article->id)->delete();

                $request->session()->flash('alert-success', trans('messages.success_delete', ['menu' => 'Artikel']));
                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_delete', ['menu' => 'Artikel'])
                ));
            }
        }
    }

    function add() {
        $menu_uri = request()->segment(1);
        $menu = $menu_uri;

        $submenu_uri = request()->segment(2);
        $submenu = $submenu_uri;

        $categories = Article_category::All();
        
        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        $data['categories'] = $categories;
        return view('article.create')->with($data);
    }

    public function create(Request $request) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'title' => 'Title',
            'category' => 'Kategori',
            'banner_image' => 'Gambar Banner'
        );

        $validator = Validator::make($request->all(), array(
                    'title' => 'required|unique:articles',
                    'category' => 'required',
                    'banner_image' => 'required|mimes:jpg,jpeg,png'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $title = $request->input('title');
            $category_id = $request->input('category');
            $description_indonesia = $request->input('description_indonesia');
            $publish = $request->input('publish');
            $product_related_ids = $request->input('product_related');

            $slug = strtolower(str_slug($title, "_"));
            $banner_image = NULL;
            $asset_request = new AssetRequest;

            if ($request->hasFile('banner_image')) {
                $banner_image_file = $request->file('banner_image');
                $banner_filename = "banner_" . $slug . "_" . uniqid();
                $banner_full_filename = "banner_" . $slug . "_" . uniqid() . "." . $banner_image_file->getClientOriginalExtension();
                $banner_filetype = $banner_image_file->getClientMimeType();
                $banner_filepath = $_FILES['banner_image']['tmp_name'];
                $destination_path = AppConfiguration::articleBannerPath()->value;

                $upload_banner_image = $asset_request->upload($banner_filepath, $banner_filetype, $banner_full_filename, $destination_path, $banner_filename);
                if ($upload_banner_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', $upload_banner_image['description']);
                } else {
                    $banner_image = $upload_banner_image['result']['file_name'];
                }
            }

            $article = new Article();
            $article->category_id = $category_id;
            $article->title = $title;
            $article->banner_image = $banner_image;
            $article->description = ($description_indonesia != "") ? $description_indonesia : NULL;
            $article->publish = ($publish != "") ? "T" : "F";
            $article->url = $slug;
            if ($article->save()) {
                $menu = $menu_uri;

                //Product Related
                if (!empty($product_related_ids)) {
                    foreach ($product_related_ids as $related_id) {
                        $article_category = New Article_product();
                        $article_category->product_id = $related_id;
                        $article_category->article_id = $article->id;
                        $article_category->save();
                    }
                }

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Artikel']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', trans('messages.failed_save', ['menu' => 'Artikel']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri)->withErrors($validator)->withInput();
        }
    }

    function edit($id) {
        $menu_uri = request()->segment(1);
        $menu = $menu_uri;

        $submenu_uri = request()->segment(2);
        $submenu = $submenu_uri;

        $article = Article::find($id);
        if (!empty($article)) {

            $categories = Article_category::All();
            
            $product_relateds = Article_product::select('article_products.product_id', 'products.name', 'products.code')
                    ->join('products', 'products.id', '=', 'article_products.product_id')
                    ->where('article_products.article_id', $article->id)
                    ->get();

            $domain = AppConfiguration::assetPortalDomain()->value;
            $path = AppConfiguration::logoPath()->value;
    
            $header = Header::find(1);
            $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
            $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

            $data['menu'] = $menu;
            $data['submenu'] = $submenu;
            $data['article'] = $article;
            $data['categories'] = $categories;
            $data['product_relateds'] = $product_relateds;

            return view('article.update')->with($data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request, $id) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'title' => 'Nama',
            'category' => 'Kategori',
            'banner_image' => 'Gambar Banner'
        );

        $article = Article::find($id);

        if ($article->title == $request->title) {
            $is_unique = "";
        } else {
            $is_unique = "|unique:articles";
        }

        $validator = Validator::make($request->all(), array(
                    'title' => 'required' . $is_unique,
                    'category' => 'required',
                    'banner_image' => 'mimes:jpeg,png',
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $title = $request->input('title');
            $category_id = $request->input('category');
            $description_indonesia = $request->input('description_indonesia');
            $publish = $request->input('publish');
            $product_related_ids = $request->input('product_related');

            $slug = strtolower(str_slug($title, "_"));
            $banner_image = $article->banner_image;
            $asset_request = new AssetRequest;


            if ($request->hasFile('banner_image')) {
                $banner_image_file = $request->file('banner_image');
                $banner_filename = "banner_" . $slug . "_" . uniqid();
                $banner_full_filename = "banner_" . $slug . "_" . uniqid() . "." . $banner_image_file->getClientOriginalExtension();
                $banner_filetype = $banner_image_file->getClientMimeType();
                $banner_filepath = $_FILES['banner_image']['tmp_name'];
                $destination_path = AppConfiguration::articleBannerPath()->value;

                $upload_banner_image = $asset_request->upload($banner_filepath, $banner_filetype, $banner_full_filename, $destination_path, $banner_filename);
                if ($upload_banner_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', $upload_banner_image['description']);
                } else {
                    if ($article->banner_image != NULL) {
                        $asset_request->delete($destination_path, $article->banner_image);
                    }
                    $banner_image = $upload_banner_image['result']['file_name'];
                }
            }

            $article->category_id = $category_id;
            $article->title = $title;
            $article->banner_image = $banner_image;
            $article->description = ($description_indonesia != "") ? $description_indonesia : NULL;
            $article->publish = ($publish != "") ? "T" : "F";
            $article->url = $slug;

            if ($article->save()) {

                //Product Related
                if (!empty($product_related_ids)) {
                    Article_product::where('article_id', $article->id)->delete();
                    foreach ($product_related_ids as $related_id) {
                        $product_related = new Article_product;
                        $product_related->article_id = $article->id;
                        $product_related->product_id = $related_id;
                        $product_related->save();
                    }
                }

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Artikel']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', trans('messages.failed_save', ['menu' => 'Artikel']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->withErrors($validator)->withInput();
        }
    }

}