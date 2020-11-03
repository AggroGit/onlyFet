<?php



	/*
	|--------------------------------------------------------------------------
	| RUTAS DE STRIPE
	|--------------------------------------------------------------------------
	|
	*/

  Route::get('/stripe/return',                          'StripeController@returnAndCreate');

  Route::group(['middleware' => ['auth:api']], function()
  {
    Route::post('/suscribe/{plan_id}',                    'SuscriptionsController@suscribeToPlan');
    //
    Route::post('/stripe/url',                            'StripeController@urlToCreate');
    Route::post('/stripe/cards',                          'StripeController@cardsList');
    Route::post('/stripe/add',                            'StripeController@addCard');
    Route::any('/pay',                                    'StripeController@pay');
    Route::any('/propina/{user_id}',                        'StripeController@sendPropina');
    //
  });
