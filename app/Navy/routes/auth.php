<?php



	/*
	|--------------------------------------------------------------------------
	| RUTAS DE AUTH
	|--------------------------------------------------------------------------
	|
	*/


// current user
Route::any('/auth',                                 'Auth\AuthController@currentUser');
Route::any('/logout',                               'Auth\AuthController@logout');
Route::post('/auth/edit',                           'Auth\AuthController@editCurrent');
Route::post('/auth/influencer',                     'SuscriptionsController@makePremium');
Route::post('/auth/plans',                          'SuscriptionsController@listPlans');
Route::post('/auth/notifications',                  'Auth\AuthController@notifications');
//
Route::post('/auth/plans/{plan_id}/unsuscribe',     'SuscriptionsController@unsuscribePlan');
// unsuscribe mails
Route::get('/unsuscribe',     'Auth\AuthController@unsuscribe'); //mails

// the upload of documents
Route::any('/register/documents/upload',   'Auth\AuthController@uploadDocuments');
Route::any('/register/influencer',         'Auth\AuthController@becomeInfluencer');
