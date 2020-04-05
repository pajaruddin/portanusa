<?php

Route::get('/inquiry', 'InquiriesController@index');
Route::post('/inquiry/form', 'InquiriesController@createInquiry');
