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
    Route::post('/userdelself/{id}', 'MenuController@menuUserDelSelf');

});

Route::prefix('admin')->group(function () {
    Route::get('menu', 'AdminController@menu')->name('admin.menu');
    Route::get('order', 'AdminController@order')->name('admin.order');
    Route::get('menu/edit/{id}', 'AdminController@menuEdit')->name('admin.menu.edit');
    Route::get('menu/category/{id}', 'AdminController@categoryEdit')->name('admin.category.edit');
    Route::post('menu/update/{id}', 'AdminController@menuUpdate')->name('admin.menu.update');
    Route::get('menu/delete/{id}', 'AdminController@menuDelete')->name('admin.menu.delete');
    Route::post('category/edit', 'AdminController@categoryUpdate')->name('admin.category.update');
    Route::get('category/delete/{id}', 'AdminController@categoryDelete')->name('admin.category.delete');


});

Route::get('/order', 'OrderController@index')->name('order')->middleware('auth');
    Route::post('/orderlist', 'MenuController@getOrders');


Route::prefix('/order_crud')->group(function () {
    Route::post('/create', 'OrderController@orderCreate');
    Route::post('/delete/{id}', 'OrderController@orderDelete');


});
