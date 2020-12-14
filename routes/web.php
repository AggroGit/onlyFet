<?php

use Illuminate\Support\Facades\Route;
use App\Navy\Enterprise;
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

Route::get('admin/login', 'Auth\AuthController@classicLogin');
 Route::post('admin/login', 'Auth\AuthController@login');
 Route::get('admin/logout', 'Auth\AuthController@logout');
Enterprise::admin();




Route::any('/forget',                       'Auth\AuthController@requestChangePassword');
Route::get('/password',                       'Auth\AuthController@forgetView');
Route::post('/password',                       'Auth\AuthController@changePass');

// RRSS
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')->name('social.auth');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('social.callback');

// any
Route::get('/{any}',    'HomeController@index')->where('any', '.*');
Auth::routes();
