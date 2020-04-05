<?php

Route::get ('/article/{url}', 'ArticlesController@index');
Route::get ('/article/{url}/{articleUrl}', 'ArticlesController@page');