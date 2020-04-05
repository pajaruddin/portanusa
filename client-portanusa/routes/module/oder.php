<?php

Route::get('/cart', 'CartsController@index');
Route::post('/cart/add', 'CartsController@add');
Route::post('/cart/delete', 'CartsController@delete');
Route::post('/cart/update', 'CartsController@update');

Route::get('/voucher', 'VouchersController@index');
Route::post('/voucher/check', 'VouchersController@check');
Route::post('/voucher/delete', 'VouchersController@delete');

Route::get('/checkout', 'CheckoutsController@index');
Route::get('/checkout/payment', 'CheckoutsController@payment');
Route::post('/checkout/form', 'CheckoutsController@formCheckout');
