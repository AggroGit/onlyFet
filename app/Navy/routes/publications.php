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
        Route::post('/publis/news',             'PublicationController@novedadesPosts');
        Route::post('/user/{user_id}/posts',   'PublicationController@postsOfUser');
        Route::post('/post/create',            'PublicationController@create');
        Route::post('/post/recomend',          'PublicationController@recomendUser');
        Route::post('/post/{post_id}/comment', 'PublicationController@comment');
        Route::post('/post/{post_id}/comments','PublicationController@comments');
        Route::post('/post/{post_id}/like',    'PublicationController@like');
        Route::post('/post/{post_id}/edit',    'PublicationController@edit');
        Route::post('/post/{post_id}/remove',  'PublicationController@remove');
        Route::post('/{post_id}/image/{name}',  'PublicationController@image');
        Route::post('/post/{post_id}/image/{name}',  'PublicationController@image');
        Route::post('/images/{name}',  'PublicationController@imagesUser');
        Route::post('/videos/{name}',  'PublicationController@videosUser');
        Route::post('/post/{post_id}',          'PublicationController@see');


    });
    Route::post('/user/{nickcname}/public',       'PublicationController@wallOfUser');

    Route::group(['middleware' => 'existPost'], function()
    {
      Route::post('/post/{post_id}/upload',   'PublicationController@upload');

    });
