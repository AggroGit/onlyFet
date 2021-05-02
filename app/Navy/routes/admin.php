<?php



	/*
	|--------------------------------------------------------------------------
	| RUTAS DE ADMIN
	|--------------------------------------------------------------------------
	|
	*/

  Route::group(['middleware' => ['auth','onlyAdmin']], function()
  {
    // validate Users
    Route::get('/admin/validateUsers/',          'AdminController@seeUsersValidate');

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
    // validate view
    Route::get('/admin/user/validate/{user_id}/',    'AdminController@validateView');
    // validate action
    Route::post('/admin/user/validate/{user_id}/',    'AdminController@validateUser');
    // chats
    Route::get('/admin/chat/edit/{chat_id}/',    'AdminController@chatView');
    // validation
    Route::post('/admin/user/validation/{user_id}/refuse',    'AdminController@refuseUser');




    // DEFAULT
    Route::get('/admin',                        'AdminController@dashboard');
    Route::get('/admin/{modelName}',            'AdminController@listModel');
    Route::get('/admin/{modelName}/add',        'AdminController@addModel');
    Route::post('/admin/{modelName}/add',       'AdminController@addDataModel');
    Route::get('/admin/{modelName}/edit/{id}',  'AdminController@editModelExists');
    Route::any('/admin/{modelName}/remove/{id}','AdminController@deleteDataModel');
    // logout
    Route::any('/admin/logout',                               'Auth\AuthController@logout');


});
