<?php

namespace App\Services\Chats;

use App\Services\Chats\ChatDomain;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Events\BoubleNotifications;

use App\Events\MessageEvent;
use App\Publication as Post;
use Carbon\Carbon;
use App\Message;
use App\Plan;
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
    $message = $this->createMessage($user, $chat, $request->message);
    // if there are an image then add it
    if($request->has('image')) {
      $this->addImageToMessage($message, $request);
    }
    // notify to the users
    $this->sendMessageNotification($message->chat->otherUser,$message);
    // broadcast
    $this->broadcastMessage($message);
    // update the time
    $chat->updated_at = now();
    $chat->save();
    return true;
  }

  // if you have a chat return it, if not then create one
  public function giveMeorCreateChatWith(User $user, $userFrom = null) :Chat
  {
    // if not is the current
    $userFrom = $userFrom?? auth()->user();

    foreach ($userFrom->chats as $chat) {
      if($chat->isUser($user)) {
        return $chat;
      }
    }
    // create array
    $users = array($userFrom,$user);
    // create one with users of array
    return $this->createChat($users);


  }

  public function closeChat(Chat $chat)
  {
    $this->close($chat);
  }

  public function uploadMedia($media_hash,Request $request)
  {
    $this->uploadImageWithToken($media_hash, $request);
  }

  public function sendMessageByToken(Chat $chat, $token, Request $request)
  {
    $message = $this->createMessageWithToken($chat,$token,$request);
    $this->loadMediaToMessage($token,$message);
    $this->broadcastMessage($message);
  }

  public function unlockMessage($message)
  {
    if(auth()->user()->card_brand == null or auth()->user()->card_last_four == null)
      throw new \Exception('Falta método de pago',200);
    if($message->forPay !== true)
      throw new \Exception('Este mensaje no es de pago',103);
    if(!auth()->user()->cobrar($message->price))
      throw new \Exception('El método de pago ha fallado',201);

    $this->unblockTheMessage($message);
    return true;

  }

  // return the messages of the chat and mark all readed
  public function getAllChat(Chat $chat)
  {
    // mark messages as read
    $this->markMessagesRead($chat);
    //
    $this->quitNotificationsChat($chat);
    //
    broadcast(new BoubleNotifications());


    return [
      "messages"  =>  $chat->messages()->paginate(1000),
      "otherUser" =>  $chat->otherUser,
      "chatData"  =>  $chat
    ];
  }

  // massive message to users
  public function sendMassiveMessage($userFrom, $token, Request $request)
  {
    // usuarios de un plan
    $num = 0;
    $planes = Plan::where('user_id',$userFrom->id)->get();
    foreach ($planes as $plan) {
      foreach ($plan->usersSuscribed as $userSuscribed) {
        $num++;
        // cogemos el chat de los dos usuarios
        $chatComun = $this->giveMeorCreateChatWith($userFrom,$userSuscribed);
        //
        $message = $this->createMessage($userFrom,$chatComun,$request->message);
        // adjuntamos media
        $this->loadMediaToMessage($token,$message);
        // notify to the users
        $this->sendMessageNotification($message->chat->otherUser,$message);
        // broadcast
        $this->broadcastMessage($message);
      }
    }
    return $num;
  }

  public function sendWelcomeMessage(User $userFrom,Chat $chat)
  {
      if($userFrom->welcomeMessage == null or $userFrom->welcomeMessage == "") {
        return false;
      }
      $message = $this->createMessage($userFrom, $chat, $userFrom->welcomeMessage);
      // notify to the users
      $this->sendMessageNotification($chat->otherUser,$message);
      //
      return true;

  }





}
