<?php



	/*
	|--------------------------------------------------------------------------
	| RUTAS DE AUTH
	|--------------------------------------------------------------------------
	|
	*/


// current user
Route::any('/auth',         'Auth\AuthController@currentUser');
Route::any('/logout',       'Auth\AuthController@logout');
Route::post('/auth/edit',     'Auth\AuthController@editCurrent');
Route::post('/auth/influencer',     'SuscriptionsController@makePremium');

// unsuscribe mails
Route::get('/unsuscribe',     'Auth\AuthController@unsuscribe');
