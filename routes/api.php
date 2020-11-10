<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Navy\Enterprise;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// login and register routes
Enterprise::noAuth();
Enterprise::stripe();
Enterprise::publications();

Route::post('/upload',     'TestController@video');

Route::group(['middleware' => ['auth:api','hasStripe']], function()
{
  //
  Enterprise::chats();
  Enterprise::auth();
  //
  Route::post('/main/users',  'ProfilesController@getAllUsers');
  Route::post('/faqs',        'faqsConttroller@getFaqs');
  //
});
Route::post('/main/nologged/users',  'ProfilesController@getAllUsers');

Route::post('/faqs',        'faqsConttroller@getFaqs');
