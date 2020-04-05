<?php

Route::get ('/download', 'DownloadsController@index');
Route::get ('/download/{url}', 'DownloadsController@page');