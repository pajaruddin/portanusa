<?php

namespace App\Libraries;

use App\Product;
use Session;

class DisplayProduct
{

  public static function getList()
  {

    $filter = (Session::has('filterProduct') ? Session::get('filterProduct') : "");
    $paginate = 20;
    $status = 1;
    if (!empty($filter['paginate'])) {
      $paginate = $filter['paginate'];
    }
    if (!empty($filter['statusProduct'])) {
      $status = $filter['statusProduct'];
    }
    $today = date('Y-m-d H:i:s');

    $getProducts = new Product;
    $getProducts = $getProducts->select('products.id', 'products.name', 'code', 'stock_status_id', 'product_stock_status.name as stock_status', 'products.date_start_periode', 'products.date_end_periode', 'products.url as url', 'product_image.image as image', 'cat_1.name as category_name', 'cat_1.url as category_url', 'cat_2.id as category_parent_1', 'cat_3.id as category_parent_2', 'able_to_order', 'products.type_status_id', 'products.status', 'products.pre_order_text')->leftJoin('product_image', 'product_image.product_id', '=', 'products.id')->leftJoin('sale_event_products', 'sale_event_products.product_id', '=', 'products.id')->leftJoin('categories as cat_1', 'cat_1.id', '=', 'products.category_id')->leftJoin('categories as cat_2', 'cat_2.id', '=', 'cat_1.parent')->leftJoin('categories as cat_3', 'cat_3.id', '=', 'cat_2.parent')->leftJoin('subjects', 'subjects.id', '=', 'products.subject_id')->leftJoin('product_stock_status', 'product_stock_status.id', '=', 'products.stock_status_id');

    // category filter
    if (!empty($filter['category'])) {
      $where = "(cat_3.id = " . $filter['category'] . " OR cat_2.id = " . $filter['category'] . " OR cat_1.id = " . $filter['category'] . ")";
      $getProducts = $getProducts->whereRaw($where);
    }

    // subject filter
    if (!empty($filter['subject'])) {
      $getProducts = $getProducts->where('subject_id', $filter['subject']);
    }

    // Pre order filter
    if (!empty($filter['stockStatus'])) {
      $getProducts = $getProducts->where('stock_status_id', $filter['stockStatus']);
      if ($filter['stockStatus'] == 2) {
        $getProducts = $getProducts->where('products.date_start_periode', '<=', $today);
        $getProducts = $getProducts->where('products.date_end_periode', '>=', $today);
      }
    }

    // Product status filter
    if (!empty($filter['status'])) {
      $getProducts = $getProducts->where('products.status', $filter['status']);
    }

    // Product status promo filter
    if (!empty($filter['status_promo'])) {
      $getProducts = $getProducts->where('products.status_promo', $filter['status_promo']);
    }

    // Price filter
    if (!empty($filter['price']) && $filter['price'] != 0) {
      if ($filter['price'] < 10000000) {
        $getProducts = $getProducts->where('price', '<=', $filter['price']);
      } else {
        $getProducts = $getProducts->where('price', '>=', $filter['price']);
      }
    }

    // search filter
    if (!empty($filter['search'])) {
      $where = "(products.name LIKE '%" . $filter['search'] . "%' OR products.code LIKE '%" . $filter['search'] . "%' OR subjects.name LIKE '%" . $filter['search'] . "%')";
      $getProducts = $getProducts->whereRaw($where);
    }

    // event filter
    if (!empty($filter['event'])) {
      $getProducts = $getProducts->where('sale_event_products.sale_event_id', $filter['event']);
    }

    // package or base
    if ($status == 2) {
      $getProducts = $getProducts->where('type_status_id', $status);
      $getProducts = $getProducts->whereRaw("(products.date_start_periode IS NULL OR products.date_start_periode <= '" . $today . "')");
      $getProducts = $getProducts->whereRaw("(products.date_end_periode IS NULL OR products.date_end_periode >= '" . $today . "')");
    } else {
      $getProducts = $getProducts->where('type_status_id', 1);
    }

    $getProducts = $getProducts->where('product_image.position', 0);
    $getProducts = $getProducts->where('products.publish', 'T')->where('deleted', 'F');

    if (!empty($filter['orderBy'])) {
      if ($filter['orderBy'] == "alphabet") {
        $getProducts = $getProducts->orderBy('name', 'asc');
      } else if ($filter['orderBy'] == "high_price") {
        $getProducts = $getProducts->orderBy('price', 'desc');
      } else if ($filter['orderBy'] == "low_price") {
        $getProducts = $getProducts->orderBy('price', 'asc');
      } else {
        $getProducts = $getProducts->orderBy('products.created_at', 'desc');
      }
    } else {
      $getProducts = $getProducts->orderBy('products.created_at', 'desc');
    }

    $getProducts = $getProducts->groupBy('products.id');
    $getProducts = $getProducts->paginate($paginate);

    return $getProducts;
  }

  public static function getListbyType($type = "")
  {

    $filter = (Session::has('filterProduct') ? Session::get('filterProduct') : "");
    $today = date('Y-m-d H:i:s');
    $status = 1;
    if (!empty($filter['statusProduct'])) {
      $status = $filter['statusProduct'];
    }

    $getProducts = new Product;
    $getProducts = $getProducts->select('products.id', 'products.name', 'code', 'stock_status_id', 'product_stock_status.name as stock_status', 'products.date_start_periode', 'products.date_end_periode', 'products.url as url', 'product_image.image as image', 'cat_2.id as category_parent_1', 'cat_3.id as category_parent_2', 'able_to_order', 'products.type_status_id', 'products.status', 'products.pre_order_text')->leftJoin('product_image', 'product_image.product_id', '=', 'products.id')->leftJoin('sale_event_products', 'sale_event_products.product_id', '=', 'products.id')->leftJoin('categories as cat_1', 'cat_1.id', '=', 'products.category_id')->leftJoin('categories as cat_2', 'cat_2.id', '=', 'cat_1.parent')->leftJoin('categories as cat_3', 'cat_3.id', '=', 'cat_2.parent')->leftJoin('subjects', 'subjects.id', '=', 'products.subject_id')->leftJoin('product_stock_status', 'product_stock_status.id', '=', 'products.stock_status_id');

    // category filter
    if (!empty($filter['category']) && $type == "category") {
      $where = "(cat_3.id = " . $filter['category'] . " OR cat_2.id = " . $filter['category'] . " OR cat_1.id = " . $filter['category'] . ")";
      $getProducts = $getProducts->whereRaw($where);
    }

    // subject filter
    if (!empty($filter['subject']) && $type == "subject") {
      $getProducts = $getProducts->where('subject_id', $filter['subject']);
    }

    // search filter
    if (!empty($filter['search']) && $type == "search") {
      $where = "(products.name LIKE '%" . $filter['search'] . "%' OR products.code LIKE '%" . $filter['search'] . "%' OR subjects.name LIKE '%" . $filter['search'] . "%')";
      $getProducts = $getProducts->whereRaw($where);
    }

    // event filter
    if (!empty($filter['event']) && $type == "event") {
      $getProducts = $getProducts->where('sale_event_products.sale_event_id', $filter['event']);
    }

    // status filter
    if (!empty($filter['status']) && $type == "status") {
      $getProducts = $getProducts->where('products.status', $filter['status']);
    }

    // Product status promo filter
    if (!empty($filter['status_promo']) && $type == "status_promo") {
      $getProducts = $getProducts->where('products.status_promo', $filter['status_promo']);
    }

    if ($status == 2) {
      $getProducts = $getProducts->where('type_status_id', $status);
      $getProducts = $getProducts->whereRaw("(products.date_start_periode IS NULL OR products.date_start_periode <= '" . $today . "')");
      $getProducts = $getProducts->whereRaw("(products.date_end_periode IS NULL OR products.date_end_periode >= '" . $today . "')");
    } else {
      $getProducts = $getProducts->where('type_status_id', 1);
    }

    $getProducts = $getProducts->where('product_image.position', 0);
    $getProducts = $getProducts->where('products.publish', 'T')->where('deleted', 'F');

    $getProducts = $getProducts->groupBy('products.id');

    $getProducts = $getProducts->get();

    return $getProducts;
  }
}
