<?php

Route::get('/about_us', 'AboutUsController@index');
Route::post('/about_us/form', 'AboutUsController@createApplyed');
