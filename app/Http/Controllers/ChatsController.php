<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Message;
use App\Chat;

class ChatsController extends Controller
{

    public function __construct()
    {
      Carbon::setLocale(auth()->user()->lang?? 'es');
        // $this->middleware('auth');
    }
    // it sends the message
    public function send(int $chat_id,Request $request)
    {
      // validation
      if ($missings = $this->hasError($request->all(),'validation.sendChat')) {
        return $this->incorrect(0,$missings);
      }
      $message = new Message();
      // we create the message
      $message->createMessage($request,$chat_id);
      $message->refresh();
      return $this->correct();

    }

    public function chats()
    {
      return $this->correct(auth()->user()->chats);
    }


    public function chat(int $chat_id)
    {
      return $this->correct(Chat::find($chat_id));
    }

    public function messages(int $chat_id)
    {
      $chat = Chat::find($chat_id);
      $chat->messages = $chat->messagesPaginated();
      return $this->correct($chat);
    }

    function removeChats(Request $request) {
      Chat::whereIn(
        'id', $request->ids
      )->delete();
      return $this->chats();
    }






}
