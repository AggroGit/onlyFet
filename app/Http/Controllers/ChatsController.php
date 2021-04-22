<?php

namespace App\Http\Controllers;

use App\Services\Chats\ChatsServiceProvider;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Message;
use App\Report as Reporte;
use App\Report;
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

    public function multipleMedia($token,Request $request)
    {
      $this->provider->uploadMedia($token,$request);
      return $this->correct();
    }

    public function createMediaMessage(int $chat_id, $token,Request $request)
    {
      $chat = Chat::find($chat_id);
      $this->provider->sendMessageByToken($chat,$token,$request);
      return $this->correct();
    }

    public function sendMassiveMessage($token, Request $request)
    {
      try {
        return $this->correct($this->provider->sendMassiveMessage(auth()->user(),$token,$request));
      } catch (\Exception $e) {
        return $this->incorrect($e->getCode());
      }
    }

    public function unlockMessage(int $chat_id, $message_id, Request $request)
    {
      if(!$message = Message::find($message_id)) {
        return $this->incorrect();
      }
      try {
        $this->provider->unlockMessage($message);
      } catch (\Exception $e) {
        return $this->incorrect($e->getCode());
      }
      return $this->correct();

    }

    public function report(int $chat_id,Request $request)
    {
      $report = new Reporte([
        "reportText" => $request->reportText,
        "user_id" => auth()->user()->id,
        "to_user_id" => $request->to_user_id
      ]);
      $report->save();
      return $this->correct();
    }






}
