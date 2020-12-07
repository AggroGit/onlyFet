<?php


use Illuminate\Support\Facades\Broadcast;

use App\Services\Auction\AuctionServiceProvider;
use App\Auction;
use App\Chat;
use App\User;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/


// channel User
Broadcast::channel('{app_id}.User.{id}', function ($user,$app_id,$id) {
    return auth()->user()->id == $id?
    auth()->user():false;
});

// channel Chat
Broadcast::channel('{app_id}.Chat.{id}', function ($user,$app_id, $id) {
    return (($chat = Chat::find($id)) and $chat->conditions())?
    auth()->user() : false;
});

// channel App
Broadcast::channel('{app_id}.App', function ($user,$app_id) {
    return auth()->user();
});

// channel auctions
Broadcast::channel('{app_id}.Auction.{id}', function ($user,$app_id, $id) {
    //
    $provider = new AuctionServiceProvider();
    return  ($auction = Auction::find($id) and ($provider->canUserBidUp($auction,$user) or $user->id == $auction->user->id)) ?
    $auction : false;
});


// channel Publication
Broadcast::channel('{app_id}.Publication.{id}', function ($user,$app_id, $id) {
    return true;
});

//
