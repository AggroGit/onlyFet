<?php



	/*
	|--------------------------------------------------------------------------
	| RUTAS DE ADMIN
	|--------------------------------------------------------------------------
	|
	*/

  Route::group(['middleware' => ['auth','onlyAdmin']], function()
  {
    // CUSTOM
    // ENTREGAS
    Route::any('/prof/purchase/{purchase_id}/deliver','AdminController@deliver');
    Route::any('/admin/purchase/edit/{purchase_id}', 'AdminController@purchaseView');
    // publicaciones
    Route::get('/admin/publication/edit/{post_id}/',    'AdminController@publicationView');
    // planes
    Route::get('/admin/plan/edit/{plan_id}/',    'AdminController@planmView');
    // user
    Route::get('/admin/user/edit/{user_id}/',    'AdminController@userView');


    // DEFAULT
    Route::get('/admin',                        'AdminController@dashboard');
    Route::get('/admin/{modelName}',            'AdminController@listModel');
    Route::get('/admin/{modelName}/add',        'AdminController@addModel');
    Route::post('/admin/{modelName}/add',       'AdminController@addDataModel');
    Route::get('/admin/{modelName}/edit/{id}',  'AdminController@editModelExists');
    Route::any('/admin/{modelName}/remove/{id}','AdminController@deleteDataModel');

});
