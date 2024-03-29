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

Route::any('/upload',           'TestController@video');

Route::post('/image/upload',     'TestController@video');
Route::any('/auction/upload',                   'AuctionController@uploadForDestroy');
Route::delete('/auction/upload/{image_id}/delete',                   'AuctionController@uploadForDestroy');


Enterprise::products();


Route::group(['middleware' => ['auth:api','hasStripe']], function()
{

  Route::post('/faqs',        'faqsConttroller@getFaqs');

  Route::post('/product/{product_id}/add',  'productsController@addToCart');
  Route::post('/cart',                      'productsController@getCart');
  Route::post('/buy',                       'productsController@buy');
  Route::post('/history',                   'productsController@history');
  Route::post('/cart/remove',               'productsController@removeFromCart');
  Route::post('/fav/{user_id}',             'ProfilesController@addFavorite');



  Enterprise::auctions();
  Enterprise::chats();
  Enterprise::auth();
  //
  Route::post('/main/users',  'ProfilesController@getAllUsers');
  Route::post('/faqs',        'faqsConttroller@getFaqs');
  //
});
Route::post('/main/nologged/users',  'ProfilesController@getAllUsers');
