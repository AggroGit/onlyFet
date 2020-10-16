<?php



	/*
	|--------------------------------------------------------------------------
	| RUTAS DE LOGIN Y REGISTRO
	|--------------------------------------------------------------------------
	|
	*/

  // auth
  Route::post('/login',                        'Auth\AuthController@login');
  Route::post('/register',                     'Auth\AuthController@register');
  Route::post('/login/social/{social}',        'Auth\AuthController@loginRRSS');
  Route::post('/register/social/{social}',     'Auth\AuthController@registerRRSS');
  Route::post('/forget',                       'Auth\AuthController@requestChangePassword');
