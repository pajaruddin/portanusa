<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Brand;
use App\Category;
use App\Subject;
use App\Product;
use App\Product_image;
use App\Product_related;
use App\Product_stock_status;
use App\Header;

use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ProductController extends Controller {

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        return view('product.list')->with($data);
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $products = Product::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'products.id', 'products.name', 'products.code', 'products.price', 'products.discount', 'products.weight', 'products.publish', 'products.able_to_order', 'products.updated_at', 'categories.name as category_name')
                ->leftjoin('categories', 'categories.id', '=', 'products.category_id')
                ->where('type_status_id', 1)
                ->orderBy('updated_at', 'desc')
                ->get();

        return Datatables::of($products)
                ->editColumn('price', function($product) {
                    if ($product->price != null) {
                        return number_format($product->price, 0, ",", ".");
                    }
                })
                ->editColumn('discount', function($product) {
                    if ($product->discount != null) {
                        return $product->discount . "%";
                    }
                    else{
                        return 'Not Discount';
                    }
                })
                ->editColumn('active', function ($product) {
                    if ($product->publish == 'T') {
                        $active_checked = 'checked';
                    } else {
                        $active_checked = '';
                    }
                    $active = '<input type="checkbox" name="active" class="make-switch" data-id="' . $product->id . '" data-size="mini" ' . $active_checked . '>';
                    
                    return $active;
                })
                ->editColumn('able', function ($product) {
                    if ($product->able_to_order == 'T') {
                        $able_checked = 'checked';
                    } else {
                        $able_checked = '';
                    }
                    $able = '<input type="checkbox" name="able" class="make-switch" data-id="' . $product->id . '" data-size="mini" ' . $able_checked . '>';
                    
                    return $able;
                })
                ->addColumn('action', function ($product) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    // $action_button .= '<a href="' . url($menu . "/detail-product", $product->id) . '" class="btn btn-circle btn-icon-only btn-primary" title="Detail Produk"><i class="fa fa-search"></i></a>';
                    $action_button .= '<a href="' . url($menu . "/edit-product", $product->id) . '" class="btn btn-circle btn-icon-only btn-warning" title="Edit Produk"><i class="fa fa-pencil"></i></a>';
                    $action_button .= '<a href="#" id="delete" data-id="' . $product->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Produk"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['active', 'able', 'action'])
                ->make(true);
    }

    function updateActive(Request $request) {
        if ($request->ajax()) {
            $product = Product::find($request->id);
            $active = $request->input('active');
            
            $product->publish = $active;
            
            if ($product->save()) {

                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_save', ['menu' => 'Produk'])
                ));
            }
        }
    }

    function updateAble(Request $request) {
        if ($request->ajax()) {
            $product = Product::find($request->id);
            $able = $request->input('able');
            
            $product->able_to_order = $able;
            
            if ($product->save()) {

                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_save', ['menu' => 'Produk'])
                ));
            }
        }
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $product = Product::find($request->id);

            if ($product->delete()) {
                $asset_request = new AssetRequest;
                $images = Product_image::where('product_id', $product->id)->get();
                $product_related = Product_related::where('product_id', $product->id)->get();

                if (!empty($product_related)) {
                    foreach($product_related as $related) {
                        $delete = $related->where('product_id', $product->id)->delete();
                    }
                }

                if (!empty($images)) {
                    foreach($images as $image) {
                        if($image->delete()){
                            $destination_path = AppConfiguration::productImagePath()->value;
                            $asset_request->delete($destination_path, $image->image);
                        }
                    }
                }

                $request->session()->flash('alert-success', trans('messages.success_delete', ['menu' => 'Produk']));
                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_delete', ['menu' => 'Produk'])
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

    function checkCode(Request $request) {
        if ($request->ajax()) {
            $code = $request->input('code');

            $check_exist = Product::where('code', $code)->count();
            if ($check_exist > 0) {
                return "false";
            } else {
                return "true";
            }
        }
    }

    function add() {
        $menu_uri = request()->segment(1);
        $menu = $menu_uri;

        $submenu_uri = request()->segment(2);
        $submenu = $submenu_uri;

        $brands = Brand::All();
        $categories = Category::all();
        $subjects = Subject::all();
        $stock_status = Product_stock_status::All();

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        $data['brands'] = $brands;
        $data['categories'] = $categories;
        $data['subjects'] = $subjects;
        $data['stock_status'] = $stock_status;
        return view('product.create')->with($data);
    }

    public function create(Request $request) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            // 'category' => 'Kategori',
            // 'subject' => 'Subject',
            // 'name' => 'Nama Produk',
            // 'code' => 'Kode Produk',
            // 'price' => 'Harga Produk',
            // 'weight' => 'Berat Produk',
            // 'highlight_indonesia' => 'Highlight',
            // 'description_indonesia' => 'Deskripsi',
        );

        $validator = Validator::make($request->all(), array(
                    // 'category' => 'required',
                    // 'subject' => 'required',
                    // 'name' => 'required',
                    // 'code' => 'required|unique:products',
                    // 'price' => 'required',
                    // 'weight' => 'required',
                    // 'highlight_indonesia' => 'required',
                    // 'description_indonesia' => 'required',
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $type_status_id = 1;
            $category_id = $request->input('category');
            $subject_id = $request->input('subject');
            $name = $request->input('name');
            $code = ($request->input('code') != "") ? $request->input('code') : str_random(4);
            $price = ($request->input('price') != "") ? $request->input('price') : 0;
            $discount = $request->input('discount');
            $weight = $request->input('weight');
            $highlight_indonesia = $request->input('highlight_indonesia');
            $description_indonesia = $request->input('description_indonesia');
            $stock_status_id = $request->input('stock_status');
            $date_start_periode = $request->input('date_start_periode');
            $date_end_periode = $request->input('date_end_periode');
            $publish = $request->input('publish');
            $orderable = $request->input('able_to_order');
            $product_related_ids = $request->input('product_related');
            $meta_description = $request->input('meta_description');
            $meta_keyword = $request->input('meta_keyword');
            $unit_image = $request->input('unit_image');
            $pre_order_text = $request->input('pre_order_text');
            $status = $request->input('status');
            $status_promo = ($request->input('status_promo') != "") ? $request->input('status_promo') : Null;
            $capital_price = ($request->input('capital_price') != "") ? $request->input('capital_price') : 0;;
            $today = date('Y-m-d H:i:s');

            $slug = strtolower(str_slug($code, "_"));
            $unit_images = [];
            $asset_request = new AssetRequest;

            $unit_image_files = $request->file('unit_image');
            $destination_unit_path = AppConfiguration::productImagePath()->value;
            if (!empty($unit_image_files)) {
                foreach ($unit_image_files as $key => $val) {
                    if ($_FILES['unit_image']['error'][$key]['file'] == 0) {
                        $unit_image_file = $unit_image_files[$key]['file'];
                        $unit_image_filename = "unit_" . $slug . "_" . uniqid();
                        $unit_image_full_filename = $unit_image_filename . "." . $unit_image_file->getClientOriginalExtension();
                        $unit_image_filetype = $unit_image_file->getClientMimeType();
                        $unit_image_filepath = $_FILES['unit_image']['tmp_name'][$key]['file'];

                        $upload_unit_image = $asset_request->upload($unit_image_filepath, $unit_image_filetype, $unit_image_full_filename, $destination_unit_path, $unit_image_filename);
                        if ($upload_unit_image['code'] == 200) {
                            $unit_images[$key] = $upload_unit_image['result']['file_name'];
                        }
                    }
                }
            }

            $product = new Product();
            $product->subject_id = ($subject_id != "") ? $subject_id : NULL;
            $product->category_id = $category_id;
            $product->name = $name;
            $product->code = $code;
            $product->price = str_replace(".", "", $price);
            $product->discount = ($discount != "") ? $discount : 0;
            $product->weight = $weight;
            $product->highlight = $highlight_indonesia;
            $product->description = $description_indonesia;
            $product->type_status_id = $type_status_id;
            $product->stock_status_id = $stock_status_id;
            $product->date_start_periode = ($date_start_periode != NULL) ? date('Y-m-d', strtotime($date_start_periode)) : NULL;
            $product->date_end_periode = ($date_end_periode != NULL) ? date('Y-m-d', strtotime($date_end_periode)) : NULL;
            $product->publish = ($publish != "") ? "T" : "F";
            $product->able_to_order = ($orderable != "") ? "T" : "F";
            $product->url = $slug;
            $product->meta_description = ($meta_description != "") ? $meta_description : NULL;
            $product->meta_keyword = ($meta_keyword != "") ? $meta_keyword : NULL;
            $product->pre_order_text = ($pre_order_text != "") ? $pre_order_text : NULL;
            $product->capital_price = str_replace(".", "", $capital_price);
            $product->status = $status;
            $product->status_promo = $status_promo;
            $product->updated_at = $today;

            if ($product->save()) {
                $product_id = $product->id;
                $menu = $menu_uri;

                //Product Related
                if (!empty($product_related_ids)) {
                    foreach ($product_related_ids as $related_id) {
                        $product_related = new Product_related;
                        $product_related->product_id = $product_id;
                        $product_related->related_id = $related_id;
                        $product_related->save();
                    }
                }

                //Product Image
                if (!empty($unit_image)) {
                    foreach ($unit_image as $key => $val) {
                        if ($key == 0) {
                            continue;
                        }

                        $unit_position = (!empty($unit_image[$key]['position'])) ? $unit_image[$key]['position'] : 0;
                        $unit_image_file = (!empty($unit_images[$key])) ? $unit_images[$key] : NULL;

                        $product_image = new Product_image;
                        $product_image->product_id = $product_id;
                        $product_image->image = $unit_image_file;
                        $product_image->position = $unit_position;
                        $product_image->save();
                    }
                }

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Produk']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', trans('messages.failed_save', ['menu' => 'Produk']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri)->withErrors($validator)->withInput();
        }
    }

    public function deleteUnitImage(Request $request) {
        if ($request->ajax()) {
            $id = $request->input('id');

            $product_image = Product_image::find($id);

            $product = Product::find($product_image->product_id);
            $product_file_image = $product_image->image;
    

            if ($product_image->delete()) {
                $filepath = AppConfiguration::productImagePath()->value;
                $asset_request = new AssetRequest;
                $asset_request->delete($filepath, $product_file_image); 

                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'failed',
                            'message' => trans('messages.failed_delete', ['menu' => 'Gambar Produk'])
                ));
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }


    function edit($id) {
        $menu_uri = request()->segment(1);
        $menu = $menu_uri;

        $submenu_uri = request()->segment(2);
        $submenu = $submenu_uri;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $product = Product::find($id);
        if (!empty($product)) {

            $brands = Brand::All();
            $categories = Category::all();
            $subjects = Subject::all();
            $stock_status = Product_stock_status::All();
            
            $product_relateds = Product_related::select('product_related.related_id', 'products.name', 'products.code')
                    ->join('products', 'products.id', '=', 'product_related.related_id')
                    ->where('product_related.product_id', $product->id)
                    ->get();

            $product_images = Product_image::where('product_id', $product->id)->orderBy('position', 'ASC')->get();

            $data['menu'] = $menu;
            $data['submenu'] = $submenu;
            $data['product'] = $product;
            $data['brands'] = $brands;
            $data['subjects'] = $subjects;
            $data['categories'] = $categories;
            $data['product_relateds'] = $product_relateds;
            $data['product_images'] = $product_images;
            $data['stock_status'] = $stock_status;

            return view('product.update')->with($data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request, $id) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            // 'category' => 'Kategori',
            // 'subject' => 'Subject',
            // 'name' => 'Nama Produk',
            // 'code' => 'Kode Produk',
            // 'price' => 'Harga Produk',
            // 'weight' => 'Berat Produk',
            // 'highlight_indonesia' => 'Highlight',
            // 'description_indonesia' => 'Deskripsi',
        );

        $product = Product::find($id);

        if ($product->code != $request->input('code')) {
            $code_is_unique = '|unique:products';
        } else {
            $code_is_unique = '';
        }

        $validator = Validator::make($request->all(), array(
                    // 'category' => 'required',
                    // 'subject' => 'required',
                    // 'name' => 'required',
                    // 'code' => 'required' . $code_is_unique,
                    // 'price' => 'required',
                    // 'weight' => 'required',
                    // 'highlight_indonesia' => 'required',
                    // 'description_indonesia' => 'required',
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $type_status_id = 1;
            $category_id = $request->input('category');
            $subject_id = $request->input('subject');
            $name = $request->input('name');
            $code = ($request->input('code') != "") ? $request->input('code') : str_random(4);
            $price = ($request->input('price') != "") ? $request->input('price') : 0;
            $discount = $request->input('discount');
            $weight = $request->input('weight');
            $highlight_indonesia = $request->input('highlight_indonesia');
            $description_indonesia = $request->input('description_indonesia');
            $stock_status_id = $request->input('stock_status');
            $date_start_periode = $request->input('date_start_periode');
            $date_end_periode = $request->input('date_end_periode');
            $publish = $request->input('publish');
            $orderable = $request->input('able_to_order');
            $product_related_ids = $request->input('product_related');
            $meta_description = $request->input('meta_description');
            $meta_keyword = $request->input('meta_keyword');
            $unit_image = $request->input('unit_image');
            $pre_order_text = $request->input('pre_order_text');
            $status = $request->input('status');
            $status_promo = ($request->input('status_promo') != "") ? $request->input('status_promo') : Null;
            $capital_price = ($request->input('capital_price') != "") ? $request->input('capital_price') : 0;
            $today = date('Y-m-d H:i:s');

            if ($name == $product->name && $code == $product->code) {
                $slug = $product->url;
            } else {
                $slug = strtolower(str_slug($code, "_"));
            }
    
            $unit_images = [];
            $asset_request = new AssetRequest;


            $unit_image_files = $request->file('unit_image');
            $destination_unit_path = AppConfiguration::productImagePath()->value;
            if (!empty($unit_image_files)) {
                foreach ($unit_image_files as $key => $val) {
                    if ($_FILES['unit_image']['error'][$key]['file'] == 0) {
                        $unit_image_file = $unit_image_files[$key]['file'];
                        $unit_image_filename = "unit_" . $slug . "_" . uniqid();
                        $unit_image_full_filename = $unit_image_filename . "." . $unit_image_file->getClientOriginalExtension();
                        $unit_image_filetype = $unit_image_file->getClientMimeType();
                        $unit_image_filepath = $_FILES['unit_image']['tmp_name'][$key]['file'];

                        $upload_unit_image = $asset_request->upload($unit_image_filepath, $unit_image_filetype, $unit_image_full_filename, $destination_unit_path, $unit_image_filename);
                        if ($upload_unit_image['code'] == 200) {
                            $unit_images[$key] = $upload_unit_image['result']['file_name'];
                        }
                    }
                }
            }

            $product->subject_id = ($subject_id != "") ? $subject_id : NULL;
            $product->category_id = $category_id;
            $product->name = $name;
            $product->code = $code;
            $product->price = str_replace(".", "", $price);
            $product->discount = ($discount != "") ? $discount : 0;
            $product->weight = $weight;
            $product->highlight = $highlight_indonesia;
            $product->description = $description_indonesia;
            $product->type_status_id = $type_status_id;
            $product->stock_status_id = $stock_status_id;
            $product->date_start_periode = ($date_start_periode != NULL) ? date('Y-m-d', strtotime($date_start_periode)) : NULL;
            $product->date_end_periode = ($date_end_periode != NULL) ? date('Y-m-d', strtotime($date_end_periode)) : NULL;
            $product->publish = ($publish != "") ? "T" : "F";
            $product->able_to_order = ($orderable != "") ? "T" : "F";
            $product->url = $slug;
            $product->meta_description = ($meta_description != "") ? $meta_description : NULL;
            $product->meta_keyword = ($meta_keyword != "") ? $meta_keyword : NULL;
            $product->pre_order_text = ($pre_order_text != "") ? $pre_order_text : NULL;
            $product->capital_price = str_replace(".", "", $capital_price);
            $product->status = $status;
            $product->status_promo = $status_promo;
            $product->updated_at = $today;

            if ($product->save()) {

                //Product Related
                Product_related::where('product_id', $product->id)->delete();
                if (!empty($product_related_ids)) {
                    foreach ($product_related_ids as $related_id) {
                        $related = Product::find($related_id);

                        $product_related = new Product_related;
                        $product_related->product_id = $product->id;
                        $product_related->related_id = $related_id;
                        $product_related->save();
                    }
                }

                //Product Image
                if (!empty($unit_image)) {
                    foreach ($unit_image as $key => $val) {
                        if ($key == 0) {
                            continue;
                        }

                        $unit_image_id = (!empty($unit_image[$key]['id'])) ? $unit_image[$key]['id'] : NULL;
                        $unit_image_position = (!empty($unit_image[$key]['position'])) ? $unit_image[$key]['position'] : 0;
                        $unit_image_file = (!empty($unit_images[$key])) ? $unit_images[$key] : NULL;

                        if ($unit_image_id != NULL) {
                            $product_image = Product_image::find($unit_image_id);
                            if ($unit_image_file == NULL) {
                                $unit_image_file = $product_image->image;
                            } else {
                                if ($product_image->image != NULL) {
                                    $destination_unit_path = AppConfiguration::productImagePath()->value;
                                    $asset_request->delete($destination_unit_path, $product_image->image);
                                }
                            }
                        } else {
                            $product_image = new Product_image;
                        }

                        $product_image->product_id = $product->id;
                        $product_image->image = $unit_image_file;
                        $product_image->position = $unit_image_position;
                        $product_image->save();
                    }
                }

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Produk']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', trans('messages.failed_save', ['menu' => 'Produk']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->withErrors($validator)->withInput();
        }
    }

}