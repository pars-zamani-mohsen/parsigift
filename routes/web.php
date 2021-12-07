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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::redirect('/', '/_manager/dashboard');
Route::redirect('/_manager', '/_manager/dashboard');
Route::redirect('/home', '/_manager/dashboard');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

/* Clear laravel cache */
Route::get('/cc', 'UserController@clear');

Route::get('/_auto', 'GiftController@autoInsertGift');


/**
 * Manager panel
 */
Route::redirect('/register', '/login');
Route::group(['prefix' => '/_manager', 'middleware' => 'auth'], function(){
    Route::get('/dashboard', 'HomeController@index')->name('Dashboard');

    // Gift
    Route::resource('/gift', 'GiftController')->middleware('userlimit');
    Route::get('gift/{id}/delete', 'GiftController@destroy')->name('Gift.destroy')->middleware('userlimit');
    Route::get('gift/{id}/activation', 'GiftController@activation')->name('Gift.activation')->middleware('userlimit');
    Route::get('gift/{id}/history', 'GiftController@getHistory')->name('Gift.history');
    Route::get('gift/module/search', 'GiftController@search')->name('Gift.search');

    // GiftRequest
    Route::get('/gift_request', 'GiftRequestController@index')->middleware('userlimit');
    Route::get('gift_request/{id}/delete', 'GiftRequestController@destroy')->name('GiftRequest.destroy')->middleware('userlimit');
    Route::get('gift_request/{id}/history', 'GiftRequestController@getHistory')->name('GiftRequest.history');
    Route::get('gift_request/module/search', 'GiftRequestController@search')->name('GiftRequest.search');

    // User
    Route::resource('/user', 'UserController')->middleware('userlimit');
    Route::get('user/{id}/delete', 'UserController@destroy')->name('User.destroy')->middleware('userlimit');
    Route::get('user/{id}/history', 'UserController@getHistory')->name('User.history');
    Route::get('user/module/search', 'UserController@search')->name('User.search');

    // ActivityLog
    Route::resource('/activitylog', 'ActivityLogController')->middleware('userlimit');
    Route::get('activitylog/{id}/delete', 'ActivityLogController@destroy')->name('ActivityLog.destroy')->middleware('userlimit');

    // Recycle bin
    Route::get('recyclebin', 'RecyclebinController@index')->name('Recyclebin.index')->middleware('userlimit');
    Route::get('recyclebin/{id}', 'RecyclebinController@list')->name('Recyclebin.list')->middleware('userlimit');
    Route::get('recyclebin/{model}/{id}/delete', 'RecyclebinController@delete')->name('Recyclebin.destroy')->middleware('userlimit');
    Route::get('recyclebin/{model}/{id}/restore', 'RecyclebinController@restore')->name('Recyclebin.restore')->middleware('userlimit');
});
