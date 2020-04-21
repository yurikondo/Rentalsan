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


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// 会員ホーム画面
Route::get('/home', 'TweetsController@index')->name('home');

// 新規投稿画面
Route::get('/create', 'TweetsController@create')->name('create');

// 新規投稿
Route::post('/create', 'TweetsController@store');

// 削除
Route::delete('/delete/{tweet}','TweetsController@delete');

// 投稿更新画面
Route::post('/edit/{tweets}','TweetsController@edit');

// 投稿更新
Route::post('/update','TweetsController@update');

