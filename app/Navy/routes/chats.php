<?php



	/*
	|--------------------------------------------------------------------------
	| RUTAS DE  CHATS
	|--------------------------------------------------------------------------
	|
	*/


    Route::post('/chats',             'ChatsController@chats');
    Route::post('/chats/remove',      'ChatsController@removeChats');
    Route::any('chat/massive/{token}',                         'ChatsController@sendMassiveMessage');
    Route::any('chat/media/{token}',                 'ChatsController@multipleMedia');

    // chats
    Route::group(['prefix'=>'chat', 'middleware' => 'existChat'], function()
    {
      Route::post('{chat_id}',                              'ChatsController@chat');
      Route::post('{chat_id}/send',                         'ChatsController@send');
      Route::post('{chat_id}/messages',                     'ChatsController@messages');
      Route::post('{chat_id}/block',                        'ChatsController@block');
      Route::post('{chat_id}/unblock',                      'ChatsController@unblock');
      Route::post('{chat_id}/report',                      'ChatsController@report');

      Route::post('{chat_id}/{image_name}',                 'ChatsController@image');
      Route::any('{chat_id}/media/{token}/create',          'ChatsController@createMediaMessage');
      Route::any('{chat_id}/message/{message_id}/unlock',   'ChatsController@unlockMessage');

    });
