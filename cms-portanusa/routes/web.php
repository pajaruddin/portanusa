<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');
Route::get('/login', 'AuthController@login');
Route::get('/logout', 'AuthController@logout');
Route::get('/profile', 'ProfileController@index');

Route::post('/login', 'AuthController@authenticated');
Route::post('/profile/personal', 'ProfileController@personal');
Route::post('/profile/password', 'ProfileController@password');
Route::post('/profile/avatar', 'ProfileController@avatar');

// Dashboard
Route::get('/dashboard/data-order', 'HomeController@getLists');
Route::get('/dashboard/detail/{id}', ['uses' => 'HomeController@getDetailOrder']);
Route::post('/dashboard/detail/{id}', ['uses' => 'HomeController@update']);

// Customer
Route::get('/master-customer', ['uses' => 'CustomersController@index']);
Route::get('/master-customer/data-customer', ['uses' => 'CustomersController@getLists']);

Route::post('/master-customer/detail-customer', 'CustomersController@getDetail');
Route::post('/master-customer/active-master-customer', 'CustomersController@updateActive');
Route::post('/master-customer/detail-customer', 'CustomersController@getDetail');
Route::post('/master-customer/delete-customer', 'CustomersController@delete');

// Customer Role
Route::get('/master-customer-role', ['uses' => 'CustomersRoleController@index']);
Route::get('/master-customer-role/data-customer-role', ['uses' => 'CustomersRoleController@getLists']);
Route::get('/master-customer-role/add-customer-role', ['uses' => 'CustomersRoleController@add']);
Route::get('/master-customer-role/edit-customer-role/{id}', ['uses' => 'CustomersRoleController@edit']);

Route::post('/master-customer-role/add-customer-role', 'CustomersRoleController@create');
Route::post('/master-customer-role/edit-customer-role/{id}', 'CustomersRoleController@update');
Route::post('/master-customer-role/delete-customer-role', 'CustomersRoleController@delete');

// User
Route::get('/master-user', ['uses' => 'UsersController@index']);
Route::get('/master-user/data-user', ['uses' => 'UsersController@getLists']);
Route::get('/master-user/add-user', ['uses' => 'UsersController@add']);
Route::get('/master-user/edit-user/{id}', ['uses' => 'UsersController@edit']);

Route::post('/master-user/detail-user', 'UsersController@getDetail');
Route::post('/master-user/add-user', 'UsersController@create');
Route::post('/master-user/edit-user/{id}', 'UsersController@update');
Route::post('/master-user/delete-user', 'UsersController@delete');

// User Role
Route::get('/master-user-role', ['uses' => 'UserRoleController@index']);
Route::get('/master-user-role/data-user-role', ['uses' => 'UserRoleController@getLists']);
Route::get('/master-user-role/add-user-role', ['uses' => 'UserRoleController@add']);
Route::get('/master-user-role/edit-user-role/{id}', ['uses' => 'UserRoleController@edit']);

Route::post('/master-user-role/add-user-role', 'UserRoleController@create');
Route::post('/master-user-role/edit-user-role/{id}', 'UserRoleController@update');
Route::post('/master-user-role/delete-user-role', 'UserRoleController@delete');

// Bank
Route::get('/master-bank', ['uses' => 'BankController@index']);
Route::get('/master-bank/data-bank', ['uses' => 'BankController@getLists']);
Route::get('/master-bank/add-bank', ['uses' => 'BankController@add']);
Route::get('/master-bank/edit-bank/{id}', ['uses' => 'BankController@edit']);

Route::post('/master-bank/add-bank', 'BankController@create');
Route::post('/master-bank/edit-bank/{id}', 'BankController@update');
Route::post('/master-bank/delete-bank', 'BankController@delete');

// Brand
Route::get('/master-brand', ['uses' => 'BrandController@index']);
Route::get('/master-brand/data-brand', ['uses' => 'BrandController@getLists']);
Route::get('/master-brand/add-brand', ['uses' => 'BrandController@add']);
Route::get('/master-brand/edit-brand/{id}', ['uses' => 'BrandController@edit']);

Route::post('/master-brand/active-master-brand', 'BrandController@updateActive');
Route::post('/master-brand/add-brand', 'BrandController@create');
Route::post('/master-brand/edit-brand/{id}', 'BrandController@update');
Route::post('/master-brand/delete-brand', 'BrandController@delete');

// Category
Route::get('/master-category', ['uses' => 'CategoryController@index']);
Route::get('/master-category/data-category', ['uses' => 'CategoryController@getLists']);
Route::get('/master-category/add-category', ['uses' => 'CategoryController@add']);
Route::get('/master-category/edit-category/{id}', ['uses' => 'CategoryController@edit']);

Route::post('/master-category/active-master-category', 'CategoryController@updateActive');
Route::post('/master-category/add-category', 'CategoryController@create');
Route::post('/master-category/edit-category/{id}', 'CategoryController@update');
Route::post('/master-category/delete-category', 'CategoryController@delete');
Route::post('/master-category/search-product', ['uses' => 'CategoryController@searchProduct']);

// Subject
Route::get('/master-subject', ['uses' => 'SubjectController@index']);
Route::get('/master-subject/data-subject', ['uses' => 'SubjectController@getLists']);
Route::get('/master-subject/add-subject', ['uses' => 'SubjectController@add']);
Route::get('/master-subject/edit-subject/{id}', ['uses' => 'SubjectController@edit']);

Route::post('/master-subject/active-master-subject', 'SubjectController@updateActive');
Route::post('/master-subject/add-subject', 'SubjectController@create');
Route::post('/master-subject/edit-subject/{id}', 'SubjectController@update');
Route::post('/master-subject/delete-subject', 'SubjectController@delete');

// Voucher
Route::get('/master-voucher', ['uses' => 'VoucherController@index']);
Route::get('/master-voucher/data-voucher', ['uses' => 'VoucherController@getLists']);
Route::get('/master-voucher/add-voucher', ['uses' => 'VoucherController@add']);
Route::get('/master-voucher/edit-voucher/{id}', ['uses' => 'VoucherController@edit']);

Route::post('/master-voucher/add-voucher', 'VoucherController@create');
Route::post('/master-voucher/edit-voucher/{id}', 'VoucherController@update');
Route::post('/master-voucher/delete-voucher', 'VoucherController@delete');

// Sale Event
Route::get('/master-sale-event', ['uses' => 'SaleEventController@index']);
Route::get('/master-sale-event/data-sale-event', ['uses' => 'SaleEventController@getLists']);
Route::get('/master-sale-event/add-sale-event', ['uses' => 'SaleEventController@add']);
Route::get('/master-sale-event/edit-sale-event/{id}', ['uses' => 'SaleEventController@edit']);

Route::post('/master-sale-event/search-product', 'SaleEventController@searchProduct');
Route::post('/master-sale-event/active-sale-event', 'SaleEventController@updateActive');
Route::post('/master-sale-event/add-sale-event', 'SaleEventController@create');
Route::post('/master-sale-event/edit-sale-event/{id}', 'SaleEventController@update');
Route::post('/master-sale-event/delete-sale-event', 'SaleEventController@delete');
Route::post('/master-sale-event/delete-sale-event-product', 'SaleEventController@deleteProduct');

// Product
Route::get('/master-product', ['uses' => 'ProductController@index']);
Route::get('/master-product/data-product', ['uses' => 'ProductController@getLists']);
Route::get('/master-product/add-product', ['uses' => 'ProductController@add']);
Route::get('/master-product/edit-product/{id}', ['uses' => 'ProductController@edit']);
Route::get('/master-product/detail-product/{id}', ['uses' => 'ProductController@detail']);

Route::post('/master-product/check-product-code', 'ProductController@checkCode');
Route::post('/master-product/search-product', ['uses' => 'ProductController@searchProduct']);
Route::post('/master-product/active-master-product', 'ProductController@updateActive');
Route::post('/master-product/able-master-product', 'ProductController@updateAble');
Route::post('/master-product/add-product', 'ProductController@create');
Route::post('/master-product/edit-product/{id}', 'ProductController@update');
Route::post('/master-product/delete-product', 'ProductController@delete');
Route::post('/master-product/delete-unit-image', 'ProductController@deleteUnitImage');

// Product Package
Route::get('/master-product-package', ['uses' => 'ProductPackageController@index']);
Route::get('/master-product-package/data-product', ['uses' => 'ProductPackageController@getLists']);
Route::get('/master-product-package/add-product', ['uses' => 'ProductPackageController@add']);
Route::get('/master-product-package/edit-product/{id}', ['uses' => 'ProductPackageController@edit']);
Route::get('/master-product-package/detail-product/{id}', ['uses' => 'ProductPackageController@detail']);

Route::post('/master-product-package/check-product-name', 'ProductPackageController@checkName');
Route::post('/master-product-package/search-product', ['uses' => 'ProductPackageController@searchProduct']);
Route::post('/master-product-package/active-master-product', 'ProductPackageController@updateActive');
Route::post('/master-product-package/able-master-product', 'ProductPackageController@updateAble');
Route::post('/master-product-package/add-product', 'ProductPackageController@create');
Route::post('/master-product-package/edit-product/{id}', 'ProductPackageController@update');
Route::post('/master-product-package/delete-product', 'ProductPackageController@delete');
Route::post('/master-product-package/delete-unit-image', 'ProductPackageController@deleteUnitImage');

// Product Discuss
Route::get('/master-product-discuss', ['uses' => 'ProductDiscussController@index']);
Route::get('/master-product-discuss/data-product-discuss', ['uses' => 'ProductDiscussController@getLists']);
Route::get('/master-product-discuss/edit-product-discuss/{id}', ['uses' => 'ProductDiscussController@edit']);
Route::get('/master-product-discuss/list-child-discuss/{id}', ['uses' => 'ProductDiscussController@list']);
Route::get('/master-product-discuss/data-child-discuss/{id}', ['uses' => 'ProductDiscussController@getChildLists']);

Route::post('/master-product-discuss/active-master-discuss', 'ProductDiscussController@updateActive');
Route::post('/master-product-discuss/delete-product', 'ProductDiscussController@delete');
Route::post('/master-product-discuss/edit-product-discuss/{id}', 'ProductDiscussController@update');

// Oder
Route::get('/order', ['uses' => 'OrderController@index']);
Route::get('/order/data-order', ['uses' => 'OrderController@getLists']);
Route::get('/order/detail/{id}', ['uses' => 'OrderController@getDetailOrder']);
Route::post('/order/detail/{id}', ['uses' => 'OrderController@update']);

Route::get('/order-accept-payment', ['uses' => 'OrderController@payment']);
Route::get('/order-accept-payment/data-payment', ['uses' => 'OrderController@getListPayments']);
Route::get('/order-accept-payment/detail/{id}', ['uses' => 'OrderController@getDetailOrder']);
Route::post('/order-accept-payment/detail/{id}', ['uses' => 'OrderController@update']);

Route::get('/order-failed-payment', ['uses' => 'OrderController@failed']);
Route::get('/order-failed-payment/data-failed', ['uses' => 'OrderController@getListFaileds']);
Route::get('/order-failed-payment/detail/{id}', ['uses' => 'OrderController@getDetailOrder']);
Route::post('/order-failed-payment/detail/{id}', ['uses' => 'OrderController@update']);

Route::get('/order-shipping', ['uses' => 'OrderController@shipping']);
Route::get('/order-shipping/data-shipping', ['uses' => 'OrderController@getListShippings']);
Route::get('/order-shipping/detail/{id}', ['uses' => 'OrderController@getDetailOrder']);
Route::post('/order-shipping/detail/{id}', ['uses' => 'OrderController@update']);

Route::get('/order-success', ['uses' => 'OrderController@success']);
Route::get('/order-success/data-success', ['uses' => 'OrderController@getListSuccess']);
Route::get('/order-success/detail/{id}', ['uses' => 'OrderController@getDetailOrder']);
Route::post('/order-success/detail/{id}', ['uses' => 'OrderController@update']);

// Category Article
Route::get('/master-category-article', ['uses' => 'CategoryArticleController@index']);
Route::get('/master-category-article/data-category', ['uses' => 'CategoryArticleController@getLists']);
Route::get('/master-category-article/add-category', ['uses' => 'CategoryArticleController@add']);
Route::get('/master-category-article/edit-category/{id}', ['uses' => 'CategoryArticleController@edit']);

Route::post('/master-category-article/add-category', 'CategoryArticleController@create');
Route::post('/master-category-article/edit-category/{id}', 'CategoryArticleController@update');
Route::post('/master-category-article/delete-category', 'CategoryArticleController@delete');

// Article
Route::get('/master-article', ['uses' => 'ArticleController@index']);
Route::get('/master-article/data-article', ['uses' => 'ArticleController@getLists']);
Route::get('/master-article/add-article', ['uses' => 'ArticleController@add']);
Route::get('/master-article/edit-article/{id}', ['uses' => 'ArticleController@edit']);

Route::post('/master-article/active-master-article', 'ArticleController@updateActive');
Route::post('/master-article/add-article', 'ArticleController@create');
Route::post('/master-article/edit-article/{id}', 'ArticleController@update');
Route::post('/master-article/delete-article', 'ArticleController@delete');
Route::post('/master-article/search-product', ['uses' => 'ArticleController@searchProduct']);

// Video
Route::get('/master-video', ['uses' => 'VideosController@index']);
Route::get('/master-video/data-video', ['uses' => 'VideosController@getLists']);
Route::get('/master-video/add-video', ['uses' => 'VideosController@add']);
Route::get('/master-video/edit-video/{id}', ['uses' => 'VideosController@edit']);

Route::post('/master-video/active-master-video', 'VideosController@updateActive');
Route::post('/master-video/add-video', 'VideosController@create');
Route::post('/master-video/edit-video/{id}', 'VideosController@update');
Route::post('/master-video/delete-video', 'VideosController@delete');

// Product Catalogue
Route::get('/master-catalogue', ['uses' => 'ProductCatalogsController@index']);
Route::get('/master-catalogue/data-catalogue', ['uses' => 'ProductCatalogsController@getLists']);
Route::get('/master-catalogue/add-catalogue', ['uses' => 'ProductCatalogsController@add']);
Route::get('/master-catalogue/edit-catalogue/{id}', ['uses' => 'ProductCatalogsController@edit']);

Route::post('/master-catalogue/active-master-catalogue', 'ProductCatalogsController@updateActive');
Route::post('/master-catalogue/add-catalogue', 'ProductCatalogsController@create');
Route::post('/master-catalogue/edit-catalogue/{id}', 'ProductCatalogsController@update');
Route::post('/master-catalogue/delete-catalogue', 'ProductCatalogsController@delete');

// Report Order
Route::get('/report-order', ['uses' => 'ReportsController@index']);
Route::post('/report-order', 'ReportsController@download');

// Newsletter
Route::get('/newsletter', ['uses' => 'NewsletterController@index']);
Route::get('/newsletter/data-newsletter', ['uses' => 'NewsletterController@getLists']);
Route::get('/newsletter/export-newsletter', ['uses' => 'NewsletterController@formExport']);

Route::post('/newsletter/delete-newsletter', 'NewsletterController@delete');
Route::post('/newsletter/export-newsletter', 'NewsletterController@export');

// Inquiry
Route::get('/inquiry', ['uses' => 'InquiryController@index']);
Route::get('/inquiry/data-inquiry', ['uses' => 'InquiryController@getLists']);

Route::post('/inquiry/detail-inquiry', 'InquiryController@getDetail');
Route::post('/inquiry/delete-inquiry', 'InquiryController@delete');

// Career Post
Route::get('/career-post', ['uses' => 'CareerPostController@index']);
Route::get('/career-post/data-career', ['uses' => 'CareerPostController@getLists']);
Route::get('/career-post/add-career', ['uses' => 'CareerPostController@add']);
Route::get('/career-post/edit-career/{id}', ['uses' => 'CareerPostController@edit']);

Route::post('/career-post/active-career', 'CareerPostController@updateActive');
Route::post('/career-post/add-career', 'CareerPostController@create');
Route::post('/career-post/edit-career/{id}', 'CareerPostController@update');
Route::post('/career-post/delete-career', 'CareerPostController@delete');

// Career Aplicant
Route::get('/career-aplicant', ['uses' => 'CareerAplicantController@index']);
Route::get('/career-aplicant/data-career', ['uses' => 'CareerAplicantController@getLists']);

Route::post('/career-aplicant/delete-career', 'CareerAplicantController@delete');

// Banner
Route::get('/master-banner', ['uses' => 'BannerController@index']);
Route::get('/master-banner/data-banner', ['uses' => 'BannerController@getLists']);
Route::get('/master-banner/add-banner', ['uses' => 'BannerController@add']);
Route::get('/master-banner/edit-banner/{id}', ['uses' => 'BannerController@edit']);

Route::post('/master-banner/active-master-banner', 'BannerController@updateActive');
Route::post('/master-banner/add-banner', 'BannerController@create');
Route::post('/master-banner/edit-banner/{id}', 'BannerController@update');
Route::post('/master-banner/delete-banner', 'BannerController@delete');

// Setting
Route::get('/setting', ['uses' => 'SettingController@index']);
Route::post('/setting/update', ['uses' => 'SettingController@update']);

// E-Catalog
Route::get('/master-catalog', ['uses' => 'CatalogController@index']);
Route::get('/master-catalog/data-catalog', ['uses' => 'CatalogController@getLists']);
Route::get('/master-catalog/add-catalog', ['uses' => 'CatalogController@add']);
Route::get('/master-catalog/edit-catalog/{id}', ['uses' => 'CatalogController@edit']);

Route::post('/master-catalog/add-catalog', 'CatalogController@create');
Route::post('/master-catalog/edit-catalog/{id}', 'CatalogController@update');
Route::post('/master-catalog/delete-catalog', 'CatalogController@delete');

// Service
Route::get('/master-service', ['uses' => 'ServiceController@index']);
Route::get('/master-service/data-service', ['uses' => 'ServiceController@getLists']);
Route::get('/master-service/add-service', ['uses' => 'ServiceController@add']);
Route::get('/master-service/edit-service/{id}', ['uses' => 'ServiceController@edit']);

Route::post('/master-service/add-service', 'ServiceController@create');
Route::post('/master-service/edit-service/{id}', 'ServiceController@update');
Route::post('/master-service/delete-service', 'ServiceController@delete');