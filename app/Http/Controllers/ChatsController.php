<?php

namespace App\Http\Controllers;

use App\Services\Chats\ChatsServiceProvider;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Message;
use App\Image;
use App\Chat;

class ChatsController extends Controller
{

    public function __construct()
    {
      Carbon::setLocale(auth()->user()->lang?? 'en');
      $this->provider = new ChatsServiceProvider();
      // $this->middleware('auth');
    }
    // it sends the message
    public function send(int $chat_id,Request $request)
    {
      // validation
      if($missings = $this->hasError($request->all(),'validation.sendChat')) {
        return $this->incorrect(0,$missings);
      }
      //
      return ($code = $this->provider->sendMessageToChat(auth()->user(),$request->chat,$request) == true)?
      $this->correct() : $this->incorrect($code);

    }

    public function chats()
    {
      return $this->correct(auth()->user()->chats);
    }

    public function image($chat_id,$image_name)
    {
      if($image = Image::where('name',$image_name)->first()) {
        return $this->correct($image);
      }
      return $this->incorrect();
    }


    public function chat(int $chat_id)
    {
      return $this->correct($request->chat);
    }

    public function messages(int $chat_id,Request $request)
    {
      return $this->correct(
        $this->provider->getAllChat($request->chat)
      );
    }

    function removeChats(Request $request) {
      $this->provider->removeChatsOfUser(auth()->user(),$request);
      return $this->chats();
    }

    public function block(int $chat_id)
    {
      $chat = Chat::find($chat_id);
      if($chat->isUser(auth()->user())) {
        $chat->block($chat->otherUser);
        $chat->open = false;
        $chat->save();
      }
      return $this->correct();
    }

    public function unBlock(int $chat_id)
    {
      $chat = Chat::find($chat_id);
      if($chat->isUser(auth()->user())) {
        $chat->block($chat->otherUser,false);
        $chat->block(auth()->user(),false);
        $chat->open = true;
        $chat->save();
      }
      return $this->correct();
    }






}
