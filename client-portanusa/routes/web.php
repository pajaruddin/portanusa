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

Route::get('/', 'HomepagesController@index');
Route::get('/ecatalog', 'CataloguesController@index');
Route::get('/phpinfo', 'HomepagesController@pagePhp');

$route_files = glob(base_path() . '/routes/module/*.php');

foreach ($route_files as $file) {
    require($file);
}
