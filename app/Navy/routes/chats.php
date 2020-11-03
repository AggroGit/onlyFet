<?php



	/*
	|--------------------------------------------------------------------------
	| RUTAS DE  CHATS
	|--------------------------------------------------------------------------
	|
	*/


    Route::post('/chats',             'ChatsController@chats');
    Route::post('/chats/remove',      'ChatsController@removeChats');

    // chats
    Route::group(['prefix'=>'chat', 'middleware' => 'existChat'], function()
    {
      Route::post('{chat_id}',              'ChatsController@chat');
      Route::post('{chat_id}/send',         'ChatsController@send');
      Route::post('{chat_id}/messages',     'ChatsController@messages');
      Route::post('{chat_id}/block',        'ChatsController@block');
    });
