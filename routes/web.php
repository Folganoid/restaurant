<?php

Auth::routes();


Route::get('/', 'HomeController@index')->name('home');

Route::get('/menu', 'MenuController@index')->name('menu')->middleware('auth');

Route::prefix('/menu_crud')->group(function () {
    Route::post('/read/{id}', 'MenuController@menuRead');
    Route::post('/create', 'MenuController@menuCreate');
    Route::post('/delete/{id}', 'MenuController@menuDelete');
    Route::post('/send', 'MenuController@menuSend')->name('send');
    Route::post('/useradd', 'MenuController@menuUserAdd');
    Route::post('/userdel', 'MenuController@menuUserDel');
});

Route::prefix('admin')->group(function () {
    Route::get('menu', 'AdminController@menu')->name('admin.menu');
    Route::get('category', 'AdminController@category')->name('admin.category');
    Route::get('order', 'AdminController@order')->name('admin.order');
});

Route::get('/order', 'OrderController@index')->name('order')->middleware('auth');
