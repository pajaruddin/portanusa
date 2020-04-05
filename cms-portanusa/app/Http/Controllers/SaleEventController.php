<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Product;
use App\Sale_event;
use App\Sale_event_product;
use App\Header;

use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class SaleEventController extends Controller {

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

        return view('sale-event.list')->with($data);
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        
        $sale_events = Sale_event::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'name', 'date_start', 'date_end', 'enable')
                ->orderBy('created_at', 'DESC')
                ->get();

        return Datatables::of($sale_events)
                ->editColumn('date_start', function ($sale_event) {
                    return date('d M Y H:i:s', strtotime($sale_event->date_start));
                })
                ->editColumn('date_end', function ($sale_event) {
                    return date('d M Y H:i:s', strtotime($sale_event->date_end));
                })
                ->editColumn('enable', function ($sale_event) {
                    if ($sale_event->enable == 'T') {
                        $active_checked = 'checked';
                    } else {
                        $active_checked = '';
                    }
                    $active = '<input type="checkbox" name="enable" class="make-switch" data-id="' . $sale_event->id . '" data-size="mini" ' . $active_checked . '>';
                    
                    return $active;
                })
                ->addColumn('action', function ($sale_event) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/edit-sale-event", $sale_event->id) . '" class="btn btn-circle btn-icon-only btn-warning" title="Edit Sale Event"><i class="fa fa-pencil"></i></a>';
                    $action_button .= '<a href="#" id="delete" data-id="' . $sale_event->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Sale Event"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['enable','action'])
                ->make(true);
    }

    function updateActive(Request $request) {
        if ($request->ajax()) {
            $sale_event = Sale_event::find($request->id);
            $enable = $request->input('enable');
            
            $sale_event->enable = $enable;
            
            if ($sale_event->save()) {

                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_save', ['menu' => 'Brand'])
                ));
            }
        }
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $sale_event = Sale_event::find($request->id);
            $sale_event_product = Sale_event_product::where('sale_event_id', $sale_event->id)->get();
            if(!empty($sale_event_product)){
                foreach($sale_event_product as $product) {
                    $delete = $product->delete();
                }
            }

            if ($sale_event->delete()) {
                $asset_request = new AssetRequest;
                if ($sale_event->banner_image != NULL) {
                    $destination_path = AppConfiguration::saleEventBannerImagePath()->value;
                    $asset_request->delete($destination_path, $sale_event->banner_image);
                }

                $request->session()->flash('alert-success', trans('messages.success_delete', ['menu' => 'Sale Event']));
                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_delete', ['menu' => 'Sale Event'])
                ));
            }
        }
    }

    function add() {
        $menu_uri = request()->segment(1);
        $menu = $menu_uri;

        $submenu_uri = request()->segment(2);
        $submenu = $submenu_uri;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        return view('sale-event.create')->with($data);
    }

    public function create(Request $request) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);
        
        $attributeNames = array(
            'name' => 'Nama Sale Event',
            'date_start' => 'Tanggal Mulai',
            'date_end' => 'Tanggal Berakhir',
            'home_image' => 'Gambar Homepage',
            'banner_image' => 'Gambar Banner',
        );
        
        $validator = Validator::make($request->all(), array(
            'name' => 'required|unique:sale_events',
            'date_start' => 'required',
            'date_end' => 'required',
            'home_image' => 'mimes:jpeg,png',
            'banner_image' => 'required|mimes:jpeg,png',
        ));
        $validator->setAttributeNames($attributeNames);
        
        if (!$validator->fails()) {
            $name = $request->input('name');
            $date_start = $request->input('date_start');
            $date_end = $request->input('date_end');
            $meta_description = $request->input('meta_description');
            $meta_keyword = $request->input('meta_keyword');
            $enable = $request->input('enable');
            $product_sale_event = $request->input('product_sale_event');
            
            $slug = strtolower(str_slug($name, "_"));
            $banner_image = NULL;
            $asset_request = new AssetRequest;
            
            if ($request->hasFile('banner_image')) {
                $banner_image_file = $request->file('banner_image');
                $banner_filename = "banner_" . $slug . "_" . uniqid();
                $banner_full_filename = $banner_filename . "." . $banner_image_file->getClientOriginalExtension();
                $banner_filetype = $banner_image_file->getClientMimeType();
                $banner_filepath = $_FILES['banner_image']['tmp_name'];
                $destination_path = AppConfiguration::saleEventBannerImagePath()->value;
                
                $upload_banner_image = $asset_request->upload($banner_filepath, $banner_filetype, $banner_full_filename, $destination_path, $banner_filename);
                if ($upload_banner_image['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', $upload_banner_image['description']);
                } else {
                    $banner_image = $upload_banner_image['result']['file_name'];
                }
            }
            
            $sale_event = new Sale_event();
            $sale_event->name = $name;
            $sale_event->banner_image = $banner_image;
            $sale_event->date_start = date('Y-m-d H:i:s', strtotime($date_start));
            $sale_event->date_end = date('Y-m-d H:i:s', strtotime($date_end));
            $sale_event->meta_description = ($meta_description != "") ? $meta_description : NULL;
            $sale_event->meta_keyword = ($meta_keyword != "") ? $meta_keyword : NULL;
            $sale_event->enable = ($enable != "") ? "T" : "F";
            $sale_event->url = $slug;

            if ($sale_event->save()) {
                $sale_event_id = $sale_event->id;
                
                //Product
                if (!empty($product_sale_event)) {
                    foreach ($product_sale_event as $key => $val) {
                        $sale_event_product_id = $product_sale_event[$key]['product_id'];
                        $sale_event_discount = (!empty($product_sale_event[$key]['discount'])) ? $product_sale_event[$key]['discount'] : 0;
                        
                        $sale_event_product = new Sale_event_product();                        
                        $sale_event_product->sale_event_id = $sale_event_id;
                        $sale_event_product->product_id = $sale_event_product_id;
                        $sale_event_product->discount = $sale_event_discount;
                        $sale_event_product->save();
                        
                    }
                }
                
                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Sale Event']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', trans('messages.failed_save', ['menu' => 'Sale Event']));
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

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $sale_event = Sale_event::find($id);
        if (!empty($sale_event)) {
            
            $sale_event_products = Sale_event_product::from('sale_event_products')
                ->select('sale_event_products.id', 'sale_event_products.product_id', 'products.name', 'products.code', 'products.price', 'sale_event_products.discount')
                ->join('products', 'products.id', '=', 'sale_event_products.product_id')
                ->where('sale_event_products.sale_event_id', $sale_event->id)
                ->get();
            
            $sale_event_product_ids = [];
            if (!empty($sale_event_products)) {
                foreach ($sale_event_products as $sale_event_product) {
                    $sale_event_product_ids[] = $sale_event_product->product_id;
                }
            }
            
            $total_product_sale_event = count($sale_event_products);
            
            $data['menu'] = $menu;
            $data['submenu'] = $submenu;
            $data['sale_event'] = $sale_event;
            $data['sale_event_products'] = $sale_event_products;
            $data['sale_event_product_ids'] = json_encode($sale_event_product_ids);
            $data['total_product_sale_event'] = $total_product_sale_event;
            return view('sale-event.update')->with($data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request, $id) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);
        
        $attributeNames = array(
            'name' => 'Nama Sale Event',
            'date_start' => 'Tanggal Mulai',
            'date_end' => 'Tanggal Berakhir',
            'banner_image' => 'Gambar Banner',
        );
        
        $sale_event = Sale_event::find($id);        
        
        $sale_event_products = Sale_event_product::from('sale_event_products')
                    ->select('products.name', 'products.code', 'sale_event_products.discount')
                    ->join('products', 'products.id', '=', 'sale_event_products.product_id')
                    ->where('sale_event_products.sale_event_id', $sale_event->id)
                    ->get();
        
        if ($sale_event->name != $request->input('name')) {
            $name_is_unique = '|unique:sale_events';
        } else {
            $name_is_unique = '';
        }
        
        $validator = Validator::make($request->all(), array(
            'name' => 'required'.$name_is_unique,
            'date_start' => 'required',
            'date_end' => 'required',
            'banner_image' => 'mimes:jpeg,png'
        ));
        $validator->setAttributeNames($attributeNames);
        
        if (!$validator->fails()) {
            $name = $request->input('name');
            $date_start = $request->input('date_start');
            $date_end = $request->input('date_end');
            $meta_description = $request->input('meta_description');
            $meta_keyword = $request->input('meta_keyword');
            $enable = $request->input('enable');
            $product_sale_event = $request->input('product_sale_event');
            
            if ($name == $sale_event->name) {
                $slug = $sale_event->url;
            } else {
                $slug = strtolower(str_slug($name, "_"));
            }
            
            $banner_image = $sale_event->banner_image;
            $asset_request = new AssetRequest;
            
            if ($request->hasFile('banner_image')) {
                $banner_file = $request->file('banner_image');
                $banner_filename = "banner_" . $slug . "_" . uniqid();
                $banner_full_filename = $banner_filename . "." . $banner_file->getClientOriginalExtension();
                $banner_filetype = $banner_file->getClientMimeType();
                $banner_filepath = $_FILES['banner_image']['tmp_name'];
                $destination_path = AppConfiguration::saleEventBannerImagePath()->value;
                
                $upload_banner = $asset_request->upload($banner_filepath, $banner_filetype, $banner_full_filename, $destination_path, $banner_filename);
                if ($upload_banner['code'] != 200) {
                    return redirect($menu_uri . "/" . $submenu_uri . "/" . $id)->with('alert-error', $upload_banner['description']);
                } else {
                    if ($sale_event->banner_image != NULL) {
                        $asset_request->delete($destination_path, $sale_event->banner_image);
                    }
                    $banner_image = $upload_banner['result']['file_name'];
                }
            }
            
            $sale_event->name = $name;
            $sale_event->banner_image = $banner_image;
            $sale_event->date_start = date('Y-m-d H:i:s', strtotime($date_start));
            $sale_event->date_end = date('Y-m-d H:i:s', strtotime($date_end));
            $sale_event->meta_description = ($meta_description != "") ? $meta_description : NULL;
            $sale_event->meta_keyword = ($meta_keyword != "") ? $meta_keyword : NULL;
            $sale_event->enable = ($enable != "") ? "T" : "F";
            $sale_event->url = $slug;
            if ($sale_event->save()) {
                
                //Product
                if (!empty($product_sale_event)) {
                    foreach ($product_sale_event as $key => $val) {
                        $product_sale_event_id = (!empty($product_sale_event[$key]['id'])) ? $product_sale_event[$key]['id'] : NULL;
                        $product_sale_event_product_id = $product_sale_event[$key]['product_id'];
                        $product_sale_event_discount = (!empty($product_sale_event[$key]['discount'])) ? $product_sale_event[$key]['discount'] : 0;
                        
                        $product = Product::find($product_sale_event_product_id);                        
                        
                        if ($product_sale_event_id != NULL) {
                            $sale_event_product = Sale_event_product::find($product_sale_event_id);
                        } else {
                            $sale_event_product = new Sale_event_product();
                        }                        
                        
                        $sale_event_product->sale_event_id = $sale_event->id;
                        $sale_event_product->product_id = $product_sale_event_product_id;
                        $sale_event_product->discount = $product_sale_event_discount;
                        $sale_event_product->save();
                        
                    }
                }
            
                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Sale Event']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri . "/" . $id)->with('alert-error', trans('messages.failed_save', ['menu' => 'Sale Event']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri . "/" . $id)->withErrors($validator)->withInput();
        }
    }

    public function deleteProduct(Request $request) {
        if ($request->ajax()) {
            $id = $request->input('id');
            $sale_event_product = Sale_event_product::find($id);            
            
            if ($sale_event_product->delete()) {
                return response()->json(array(
                    'status' => 'success'
                ));
            } else {
                return response()->json(array(
                    'status' => 'failed',
                    'message' => trans('messages.failed_delete', ['menu' => 'Produk'])
                ));
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

}