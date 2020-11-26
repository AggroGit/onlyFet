<?php

namespace App\Services\Chats;

use App\Services\Chats\ChatDomain;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Events\MessageEvent;
use App\Publication as Post;
use Carbon\Carbon;
use App\Message;
use App\Chat;
use App\User;




class ChatsServiceProvider extends ChatDomain
{
  // remove the chats of the user
  public function removeChatsOfUser(User $user, Request $request)
  {
    $user->chats()->whereIn(
      'id', $request->ids
    )->delete();
  }


  // BUG: Creo que el check de si el usuario esta conectado debería estar en el DOMINIO
  public function sendMessageToChat(User $user, Chat $chat, Request $request)
  {
    //
    if(!$this->canSendToChat($user,$chat)) {
      return 102;
    }
    // create the message
    $message = $this->createMessage($user, $chat, $request);
    // if there are an image then add it
    if($request->has('image')) {
      $this->addImageToMessage($message, $request);
    }
    // notify to the users
    $this->sendMessageNotification($message->chat->otherUser,$message);
    // broadcast
    broadcast(new MessageEvent(Message::without('user')->find($message->id)));

    return true;
  }

}
