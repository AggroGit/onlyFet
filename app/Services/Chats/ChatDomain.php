<?php

namespace App\Services\Chats;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Message;
use App\Image;
use App\User;
use App\Chat;





class ChatDomain
{
  // check is user in chat
  public function isUserInChat(User $user, Chat $chat)
  {
    if($chat->users()->find($user->id)) {
      return true;
    }
    return false;
  }

  // check if you can send a message to chat
  public function canSendToChat(User $user, Chat $chat)
  {
    return ($chat->open and $this->isUserInChat($user,$chat))? true:false;
  }

  // create the message and add text
  public function createMessage(User $user, Chat $chat, Request $request) :Message
  {
    $message = new Message();
    $message->chat_id = $chat->id;
    $message->message = $request->message?? "";
    $message->user_id = $user->id;
    $message->save();
    return $message;
  }

  public function addImageToMessage(Message $message, Request $request)
  {
    // create the image
    $new = new Image();
    $new->create($request->image,"chat");
    $new->save();
    $message->image_id = $new->id;
    $message->save();
    return $message;
  }

  // send a nptification to the user
  public function sendMessageNotification(User $user, Message $message)
  {
    // if($message->chat->isUserConnected($user)) {
      $user->send([
        "title"   => $user->name,
        "body"    => "$message->message",
        "type"    => "chat",
        "data"    => $message->chat->id,
        "sound"   => "default",
      ]);
    // }
  }

}
