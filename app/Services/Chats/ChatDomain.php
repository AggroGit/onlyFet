<?php

namespace App\Services\Chats;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Events\MessageEvent;
use Carbon\Carbon;
use App\Message;
use App\Video;
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
      $user->send([
        "title"   => $message->user->name,
        "body"    => "$message->message",
        "type"    => "chat",
        "data"    => $message->chat->id,
        "sound"   => "default",
      ]);
  }

  // create chat with given users
  public function createChat($users)
  {
    $new = new Chat();
    $new->save();
    foreach ($users as $user) {
      $new->addUser($user);
    }
    // return all the object
    return Chat::find($new->id);
  }

  public function close(Chat $chat)
  {
    $chat->open = false;
    $chat->save();
  }

  public function markMessagesRead(Chat $chat, $user = null)
  {
    $user = $user?? $chat->otherUser;
    //
    $chat->messages()->where([
      'user_id' => $user->id,
      'read'  => false
      ])->update([
      'read' => true
    ]);
  }

  public function uploadImageWithToken($chat, $token, Request $request)
  {
    $image = ['file'=> 'image:mimes:jpg,jpeg,png'];
    $video = ['file'=> 'video:mimes:mp4,mov'];

    $validator = Validator::make($request->all(),$image);

    if($validator->fails()) {
      // probamos vídeo
      $video = new Video();
      $video->create($request->file,"video/$chat->id");
      $video->user_id = auth()->user()->id;
      $video->token = $token;
      $video->save();

    } else {
      // imagen
      $image = new Image();
      $image->create($request->file,"image/$chat->id");
      $image->user_id = auth()->user()->id;
      $image->token = $token;
      $image->save();
    }
  }

  public function createMessageWithToken($chat,$token, Request $request)
  {
    $new = new Message();
    $new->message = "📸";
    $new->chat_id = $chat->id;
    $new->user_id = auth()->user()->id;
    $new->token   = $token;
    if($request->forPay == true and $request->price >=1) {
      $new->forPay = true;
      $new->price = $request->price;
      $new->message = "🔒 📸";
    }
    $new->save();
    //
    $chat->updated_at = now();
    $chat->save();
    //
    return $new;
  }

  public function loadMediaToMessage($token,$message)
  {
    // get the videos with token
    $videos = Video::where('token', $token)->get();
    // get the images with token
    $images = Image::where('token', $token)->get();
    // save the videos
    foreach ($videos as $video) {
      $message->videos()->save($video);
    }
    // save the images
    foreach ($images as $image) {
      $message->images()->save($image);
    }
  }

  public function broadcastMessage($message)
  {
    broadcast(new MessageEvent(Message::without('user')->find($message->id)));

  }

  public function unblockTheMessage(Message $message)
  {
    $message->forPay = false;
    $message->message = "📸 🗝 🔓";
    $message->save();
  }

}
