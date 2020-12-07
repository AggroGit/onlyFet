<?php



	/*
	|--------------------------------------------------------------------------
	| RUTAS DE  Pujas
	|--------------------------------------------------------------------------
	|
	*/

  Route::post('/auction/create',                   'AuctionController@createAuction');
  Route::post('/auction/{auction_id}',             'AuctionController@getAuction');

  Route::post('/auction/{auction_id}/bidup',       'AuctionController@BidUp');
  Route::any('/auction/{auction_id}/upload',       'AuctionController@updateimage');
