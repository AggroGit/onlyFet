<?php



	/*
	|--------------------------------------------------------------------------
	| RUTAS DE  CHATS
	|--------------------------------------------------------------------------
	|
	*/







    Route::group(['middleware' => ['auth:api','existPost','hasStripe']], function()
    {
        Route::post('/user/{nickcname}',       'PublicationController@wallOfUser');
        Route::post('/posts',                  'PublicationController@posts');
        Route::post('/user/{user_id}/posts',   'PublicationController@postsOfUser');
        Route::post('/post/create',            'PublicationController@create');
        Route::post('/post/recomend',          'PublicationController@recomendUser');
        Route::post('/post/{post_id}/comment', 'PublicationController@comment');
        Route::post('/post/{post_id}/comments','PublicationController@comments');
        Route::post('/post/{post_id}/like',    'PublicationController@like');
        Route::post('/post/{post_id}/edit',    'PublicationController@edit');
        Route::post('/post/{post_id}/remove',  'PublicationController@remove');
    });

    Route::group(['middleware' => 'existPost'], function()
    {
      Route::post('/post/{post_id}',          'PublicationController@see');
      Route::post('/post/{post_id}/upload',   'PublicationController@upload');

    });
