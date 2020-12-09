<?php



	/*
	|--------------------------------------------------------------------------
	| RUTAS DE  Productos y tienda
	|--------------------------------------------------------------------------
	|
	*/
  Route::post('/products',                'productsController@getProducts');
  Route::post('/product/{product_id}',    'productsController@getProduct');
