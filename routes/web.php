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


//
// // Nuestras
// Enterprise::auth();
// // backOffice
// Enterprise::admin();
// // rutas de auth de laravel
// // Auth::routes();
// Route::get('/',                   'HomeController@start');
// Route::get('/home',                   'HomeController@start');
// Route::get('/success',                 'HomeController@sucess');
Route::post('/forget',                       'Auth\AuthController@requestChangePassword');

// RRSS
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')->name('social.auth');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('social.callback');


// Route::get('home',     'HomeController@index');
Route::get('/{any}',        'HomeController@index')->where('any', '.*');
Route::get('/register', 'HomeController@index');
