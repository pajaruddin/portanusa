<?php

Route::get('/category/{url}', 'ProductsController@productCategory');
Route::get('/subject/{url}', 'ProductsController@productSubject');
Route::get('/event/{url}', 'ProductsController@productEvent');
Route::get('/status/{url}', 'ProductsController@productStatus');
// Route::get('/package/deal', 'ProductsController@productPackage');
Route::get('/search/result', 'ProductsController@productSearch');

Route::post('/product/discussion', 'ProductsFormController@discussion');
Route::post('/product/wishlist', 'ProductsFormController@wishlist');

Route::post('/product/list/filter/status', 'FilterProductsController@filterStatus');
Route::post('/product/list/filter/stock-status', 'FilterProductsController@filterStockStatus');
Route::post('/product/list/filter/sort-product', 'FilterProductsController@filterSortProduct');
Route::post('/product/list/filter/price', 'FilterProductsController@filterPrice');

Route::get('/base/{url}', 'DetailProductsController@detailBase');
Route::get('/package/{url}', 'DetailProductsController@detailPackage');

Route::get('/product/{type}/{url}', 'ProductsController@productRefresh');
