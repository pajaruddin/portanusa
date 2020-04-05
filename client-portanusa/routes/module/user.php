<?php

Route::get('/logout', 'UsersController@logout');
Route::get('/activation/{activation_code}', 'UsersController@activation');
Route::get('/account', 'UsersController@account');
Route::get('/account/edit', 'UsersController@accountEdit');
Route::get('/account/password', 'UsersController@accountPassword');
Route::get('/account/shipping', 'UsersController@accountShipping');
Route::get('/account/shipping/create', 'UsersController@accountCreateShipping');
Route::get('/account/shipping/edit/{id}', 'UsersController@accountEditShipping');
Route::get('/account/wishlist', 'UsersController@accountWishlist');
Route::get('/account/history-order', 'UsersController@accountHistoryOrder');
Route::get('/account/history-order/download/{id}', 'UsersController@accountHistoryOrderDownload');
Route::post('/reset-password/form', 'UsersController@formResetPassword');
Route::get('/reset-password/{forgotten_password_code}', 'UsersController@resetPassword');
Route::post('/register/form', 'UsersController@formRegister');
Route::post('/sign-in/form', 'UsersController@formSignIn');
Route::post('/account/edit/form', 'UsersController@formAccountEdit');
Route::post('/account/password/form', 'UsersController@formAccountPassword');
Route::post('/account/shipping/add', 'UsersController@formAccountCreateShipping');
Route::post('/account/shipping/update', 'UsersController@formAccountEditShipping');
Route::post('/account/shipping/delete', 'UsersController@formAccountDeleteShipping');
Route::post('/forgot-password', 'UsersController@forgotPassword');
Route::post('/transfer-image', 'UsersController@updateStatusOrder');
Route::post('/order/update', 'UsersController@updateOrder');

Route::get('/facebook/redirect', 'SocialAuthController@facebookRedirect');
Route::get('/facebook/callback', 'SocialAuthController@facebookCallback');

Route::get('/google/redirect', 'SocialAuthController@googleRedirect');
Route::get('/google/callback', 'SocialAuthController@googleCallback');
