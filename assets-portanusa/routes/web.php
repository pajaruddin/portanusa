<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return response('Access Forbidden!!!', 403);
});

$router->get('/elfinder/connector', ['uses' =>  'ElfinderController@connector']);
$router->get('/elfinder/media', ['uses' =>  'ElfinderController@media']);
$router->post('/elfinder/connector', ['uses' =>  'ElfinderController@connector']);
$router->post('/create-directory', ['uses' =>  'AssetController@makeDirectory']);
$router->post('/delete-directory', ['uses' =>  'AssetController@deleteDirectory']);
$router->post('/upload', ['uses' =>  'AssetController@upload']);
$router->post('/delete', ['uses' =>  'AssetController@delete']);
$router->post('/move-file', ['uses' =>  'AssetController@moveFile']);

$router->post('/customer-upload', ['middleware' => 'customer_auth', 'uses' =>  'CustomerAssetController@upload']);
$router->post('/anonymous-upload', ['uses' =>  'AssetController@upload']);
$router->post('/customer-delete', ['middleware' => 'customer_auth', 'uses' =>  'CustomerAssetController@delete']);