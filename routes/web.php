<?php

Auth::routes();


Route::get('/', 'HomeController@index')->name('home');

Route::get('/menu', 'MenuController@index')->name('menu')->middleware('auth');

Route::prefix('admin')->group(function () {
    Route::get('menu', 'AdminController@menu')->name('admin.menu');
    Route::get('category', 'AdminController@category')->name('admin.category');
    Route::get('order', 'AdminController@order')->name('admin.order');
});

Route::get('/order', 'OrderController@index')->name('order')->middleware('auth');
