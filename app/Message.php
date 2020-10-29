<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\MessageEvent;
use App\Jobs\jobMessage;
use App\Image;
use App\Message;

class Message extends Model
{

    protected $with = ['user','image'];

    protected $fillable = [
      'chat_id'
    ];

    protected $appends = [
      'fecha',
    ];

    // the chat of the message
    public function chat()
    {
      return $this->belongsTo('App\Chat');
    }

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    // the image of the message
    public function image()
    {
      return $this->belongsTo('App\Image');
    }

    // send the message
    public function send()
    {
      // first we save the message
      $this->save();
      // lanzamos el event
      broadcast(new MessageEvent(Message::without('user')->find($this->id)));
      // notificamos
      $this->notifyUsers();
      // updated_at of the chat, for the order lists
      $this->chat->updated_at = now();
      $this->chat->save();
    }

    // notify the users of the chat
    public function notifyUsers()
    {
      // we take the users of the chat, except us
      $user = $this->chat->otherUser;
      // if the user is in the chat is not necessary to send the notification
      // echo $this->chat->isUserConnected($user)? "Sí" : "No";
      if(!$this->chat->isUserConnected($user)) {
        $user->send([
          "title"   => auth()->user()->name,
          "body"    => "$this->message",
          "type"    => "chat",
          "data"    => $this->chat->id,
          "sound"   => "default"
        ]);
      }

    }

    public function getFechaAttribute()
    {
      return $this->created_at->diffForHumans();
    }

    //
    public function createMessage($request,$chat_id = 0)
    {
      $this->chat_id = $chat_id;
      $this->message = $request->message?? "";
      $this->user_id = auth()->user()->id;
      if($request->has('image')) {
        $new = new Image();
        $new->create($request->image,"chat");
        $new->save();
        $this->image_id = $new->id;
      }
      $this->send();
      return $this;
    }

    //
    public function delete()
    {
        $this->image()->delete();
        return parent::delete();
    }





}
