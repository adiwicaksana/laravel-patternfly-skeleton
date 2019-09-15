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
    return redirect('login');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    /* Dashboard sebagai halaman pertama setelah login */
    Route::get('home', 'HomeController@index');

    /* My Account (Update Profile & Password) */
    Route::get('user/profile', 'AuthController@getMyAccount');
    Route::post('user/update-profile', 'AuthController@postUpdateProfile');
    Route::post('user/update-password', 'AuthController@postUpdatePassword');

    /* Datatable */
    Route::post('datatable/users', 'UserController@datatable');
    Route::post('datatable/roles', 'RoleController@datatable');
    Route::post('datatable/stores', 'StoreController@datatable');

    /* Master Data */
    Route::resource('user', 'UserController');
    Route::resource('role', 'RoleController');
    Route::resource('store', 'StoreController');

    /*
     * API Controller
     * untuk melakukan uji coba request API
     * contoh dapat dilihat pada API Controller
    */
    Route::get('get-token', 'APIController@getToken');
    Route::get('get-all-user', 'APIController@getAllUser');

});
