<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Category;
use App\Category_product;
use App\Product;
use App\Header;

use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class CategoryController extends Controller {

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        return view('category.list')->with($data);
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $categories = Category::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'name', 'description', 'publish')
                ->orderBy('id', 'desc')
                ->get();

        return Datatables::of($categories)
                ->editColumn('active', function ($category) {
                    if ($category->publish == 'T') {
                        $active_checked = 'checked';
                    } else {
                        $active_checked = '';
                    }
                    $active = '<input type="checkbox" name="active" class="make-switch" data-id="' . $category->id . '" data-size="mini" ' . $active_checked . '>';
                    
                    return $active;
                })
                ->addColumn('action', function ($category) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/edit-category", $category->id) . '" class="btn btn-circle btn-icon-only btn-warning" title="Edit Category"><i class="fa fa-pencil"></i></a>';
                    $action_button .= '<a href="#" id="delete" data-id="' . $category->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Category"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['active', 'action'])
                ->make(true);
    }

    function updateActive(Request $request) {
        if ($request->ajax()) {
            $category = Category::find($request->id);
            $active = $request->input('active');
            
            $category->publish = $active;
            
            if ($category->save()) {

                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_save', ['menu' => 'Kategori'])
                ));
            }
        }
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $category = Category::find($request->id);

            if ($category->delete()) {
                $asset_request = new AssetRequest;
                if ($category->banner_image != NULL) {
                    $destination_path = AppConfiguration::categoryBannerImagePath()->value;
                    $asset_request->delete($destination_path, $category->banner_image);
                }

                if ($category->thumb_image != NULL) {
                    $destination_path_thumb = AppConfiguration::categoryThumbImagePath()->value;
                    $asset_request->delete($destination_path_thumb, $category->thumb_image);
                }

                $request->session()->flash('alert-success', trans('messages.success_delete', ['menu' => 'Kategori']));
                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_delete', ['menu' => 'Kategori'])
                ));
            }
        }
    }

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

    function add() {
        $menu_uri = request()->segment(1);
        $menu = $menu_uri;

        $submenu_uri = request()->segment(2);
        $submenu = $submenu_uri;

        $parent_category = Category::whereNull('parent')->get();
        $child_category = Category::whereNotNull('parent')->get();

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        $data['parent_category'] = $parent_category;
        $data['child_category'] = $child_category;
        return view('category.create')->with($data);
    }

    public function create(Request $request) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'name' => 'Nama Brand',
            'banner_image' => 'Gambar Banner Image',
            'thumb_image' => 'Gambar Thumb Image'
        );

        $validator = Validator::make($request->all(), array(
            'name' => 'required|unique:categories',
            'banner_image' => 'mimes:jpg,jpeg,png',
            'thumb_image' => 'mimes:jpg,jpeg,png'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $name = $request->input('name');
            $parent = $request->input('parent');
            $description_indonesia = $request->input('description_indonesia');
            $meta_description = $request->input('meta_description');
            $meta_keyword = $request->input('meta_keyword');
            $publish = $request->input('publish');
            $display_home = $request->input('display_home');
            $highlight = $request->input('highlight');
            $product_related_ids = $request->input('product_related');

            $slug = strtolower(str_slug($name, "_"));
            $banner_image = NULL;
            $thumb_image = NULL;
            $asset_request = new AssetRequest;

            if ($request->hasFile('banner_image')) {
                $banner_image_file = $request->file('banner_image');
                $banner_filename = "banner_" . $slug . "_" . uniqid();
                $banner_full_filename = "banner_" . $slug . "_" . uniqid() . "." . $banner_image_file->getClientOriginalExtension();
                $banner_filetype = $banner_image_file->getClientMimeType();
                $banner_filepath = $_FILES['banner_image']['tmp_name'];
                $destination_path = AppConfiguration::categoryBannerImagePath()->value;

                $upload_banner_image = $asset_request->upload($banner_filepath, $banner_filetype, $banner_full_filename, $destination_path, $banner_filename);
                if ($upload_banner_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', $upload_banner_image['description']);
                } else {
                    $banner_image = $upload_banner_image['result']['file_name'];
                }
            }

            if ($request->hasFile('thumb_image')) {
                $thumb_image_file = $request->file('thumb_image');
                $thumb_filename = "thumb_" . $slug . "_" . uniqid();
                $thumb_full_filename = $thumb_filename . "." . $thumb_image_file->getClientOriginalExtension();
                $thumb_filetype = $thumb_image_file->getClientMimeType();
                $thumb_filepath = $_FILES['thumb_image']['tmp_name'];
                $destination_path = AppConfiguration::categoryThumbImagePath()->value;

                $upload_thumb_image = $asset_request->upload($thumb_filepath, $thumb_filetype, $thumb_full_filename, $destination_path, $thumb_filename);
                if ($upload_thumb_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', $upload_thumb_image['description']);
                } else {
                    $thumb_image = $upload_thumb_image['result']['file_name'];
                }
            }

            $category = new Category();
            $category->name = $name;
            $category->banner_image = $banner_image;
            $category->thumb_image = $thumb_image;
            $category->description = ($description_indonesia != "") ? $description_indonesia : NULL;
            $category->meta_description = ($meta_description != "") ? $meta_description : NULL;
            $category->meta_keyword = ($meta_keyword != "") ? $meta_keyword : NULL;
            $category->publish = ($publish != "") ? "T" : "F";
            $category->parent = ($parent != "") ? $parent : NULL;
            $category->display_home = ($display_home != "") ? "T" : "F";
            $category->highlight = ($highlight != "") ? "T" : "F";
            $category->url = $slug;
            if ($category->save()) {

                //Product Related
                if (!empty($product_related_ids)) {
                    foreach ($product_related_ids as $related_id) {
                        $product_related = new Category_product;
                        $product_related->category_id = $category->id;
                        $product_related->product_id = $related_id;
                        $product_related->save();
                    }
                }

                $menu = $menu_uri;

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Kategori']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', trans('messages.failed_save', ['menu' => 'Kategori']));
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

        $category = Category::find($id);
        $parent_category = Category::whereNull('parent')->get();
        $child_category = Category::whereNotNull('parent')->get();
        $product_relateds = Category_product::select('category_products.product_id', 'products.name', 'products.code')
            ->join('products', 'products.id', '=', 'category_products.product_id')
            ->where('category_products.category_id', $category->id)
            ->get();

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        $data['category'] = $category;
        $data['parent_category'] = $parent_category;
        $data['child_category'] = $child_category;
        $data['product_relateds'] = $product_relateds;
        return view('category.update')->with($data);
    }

    public function update(Request $request, $id) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'name' => 'Nama Kategori',
            'banner_image' => 'Gambar Banner IMage',
            'thumb_image' => 'Gambar Thumb Image',
            'ur' => 'Url Kategori'
        );

        $category = Category::find($id);

        if ($category->name == $request->name) {
            $is_unique = "";
        } else {
            $is_unique = "|unique:categories";
        }

        if ($category->url) {
            $is_url_unique = "";
        } else {
            $is_url_unique = "|unique:categories";
        }

        $validator = Validator::make($request->all(), array(
                    'name' => 'required' . $is_unique,
                    'banner_image' => 'mimes:jpeg,png',
                    'thumb_image' => 'mimes:jpeg,png',
                    'url' => $is_url_unique
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $name = $request->input('name');
            $parent = $request->input('parent');
            $description_indonesia = $request->input('description_indonesia');
            $meta_description = $request->input('meta_description');
            $meta_keyword = $request->input('meta_keyword');
            $publish = $request->input('publish');
            $display_home = $request->input('display_home');
            $highlight = $request->input('highlight');
            $product_related_ids = $request->input('product_related');

            $slug = strtolower(str_slug($name, "_"));
            $banner_image = $category->banner_image;
            $thumb_image = $category->thumb_image;
            $asset_request = new AssetRequest;


            if ($request->hasFile('banner_image')) {
                $banner_file = $request->file('banner_image');
                $banner_filename = "banner_" . $slug . "_" . uniqid();
                $banner_full_filename = $banner_filename . "." . $banner_file->getClientOriginalExtension();
                $banner_filetype = $banner_file->getClientMimeType();
                $banner_filepath = $_FILES['banner_image']['tmp_name'];
                $destination_path = AppConfiguration::categoryBannerImagePath()->value;

                $upload_banner_image = $asset_request->upload($banner_filepath, $banner_filetype, $banner_full_filename, $destination_path, $banner_filename);
                if ($upload_banner_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri . "/" . $id)->with('alert-error', $upload_banner_image['description']);
                } else {
                    if ($category->banner_image != NULL) {
                        $asset_request->delete($destination_path, $category->banner_image);
                    }
                    $banner_image = $upload_banner_image['result']['file_name'];
                }
            }

            if ($request->hasFile('thumb_image')) {
                $thumb_image_file = $request->file('thumb_image');
                $thumb_filename = "thumb_" . $slug . "_" . uniqid();
                $thumb_full_filename = $thumb_filename . "." . $thumb_image_file->getClientOriginalExtension();
                $thumb_filetype = $thumb_image_file->getClientMimeType();
                $thumb_filepath = $_FILES['thumb_image']['tmp_name'];
                $destination_path = AppConfiguration::categoryThumbImagePath()->value;

                $upload_thumb_image = $asset_request->upload($thumb_filepath, $thumb_filetype, $thumb_full_filename, $destination_path, $thumb_filename);
                if ($upload_thumb_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', $upload_thumb_image['description']);
                } else {
                    if ($category->thumb_image != NULL) {
                        $asset_request->delete($destination_path, $category->thumb_image);
                    }
                    $thumb_image = $upload_thumb_image['result']['file_name'];
                }
            }

            $category->name = $name;
            $category->banner_image = $banner_image;
            $category->thumb_image = $thumb_image;
            $category->description = ($description_indonesia != "") ? $description_indonesia : NULL;
            $category->meta_description = ($meta_description != "") ? $meta_description : NULL;
            $category->meta_keyword = ($meta_keyword != "") ? $meta_keyword : NULL;
            $category->publish = ($publish != "") ? "T" : "F";
            $category->parent = ($parent != "") ? $parent : NULL;
            $category->display_home = ($display_home != "") ? "T" : "F";
            $category->highlight = ($highlight != "") ? "T" : "F";
            $category->url = $slug;
            if ($category->save()) {

                //Product Related
                Category_product::where('category_id', $category->id)->delete();
                if (!empty($product_related_ids)) {
                    foreach ($product_related_ids as $related_id) {

                        $product_related = new Category_product;
                        $product_related->category_id = $category->id;
                        $product_related->product_id = $related_id;
                        $product_related->save();
                    }
                }

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Kategori']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', trans('messages.failed_save', ['menu' => 'Kategori']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->withErrors($validator)->withInput();
        }
    }

}