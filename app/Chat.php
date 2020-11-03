<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Sockeable;
use App\User;
use App\Chat;

class Chat extends Model
{
    use Sockeable;
    protected $with = ['users'];
    protected $appends = ['otherUser','lastMessage'];

    // messages of the chat
    public function messages()
    {
       return $this->hasMany('App\Message')
                   ->orderBy('created_at','desc');
    }

    // the messages of the chat paginated
    public function messagesPaginated()
    {
      return $this->messages()
                  ->paginate(200);
    }

    // users in the chat
    public function users()
    {
      return $this->belongsToMany('App\User', 'chats_users');
    }

    // the last message sended in the chat
    public function getLastMessageAttribute()
    {
      return $this->messages()
                  ->first();
    }

    public function getOtherUserAttribute()
    {
      return $this->users()->where('id','!=',auth()->user()->id)->first();
    }

    // delete user
    public function deleteUser(User $user)
    {
      $this->users()->detach($user);
      $this->save();
    }

    public static function giveMeorCreateChatWith($user)
    {
      $chats = $user->chats;
      foreach ($chats as $chat) {
        if($chat->isUser(auth()->user())) {
          return $chat;
        }
      }
      $new = new Chat();
      $new->save();
      $new->addUser($user);
      $new->addUser(auth()->user());
      $new->save();
      return Chat::find($new->id);
    }


    // check if is user in a chat
    public function isUser(User $user)
    {
      if ($this->users()->find($user->id)) {
        return true;
      }
      return false;
    }

    public function quitUser(User $user)
    {
      if($this->isUser($user)) {
        $this->users()->detach($user);
        return $this->save();
      }
    }

    // we pass the user and if we want to quit the hidden we pass the second param
    public function hidden(User $user,$bool = true)
    {
      if($this->isUser($user)) {
        $this->users()->updateExistingPivot($user->id,[
          "hidden" => $bool
        ]);
        return $this->save();
      }
    }

    // we pass the user and if we want to quit the hidden we pass the second param
    public function block(User $user,$bool = true)
    {
      if($this->isUser($user)) {
        $this->users()->updateExistingPivot($user->id,[
          "blocked" => $bool
        ]);
        return $this->save();
      }
    }

    public function addUser(User $user)
    {
      if (!$this->isUser($user)) {
        $this->users()->save($user);
        return $this->save();
      }
      return false;
    }

    // conditions for the connection to the chat
    public function conditions()
    {
      // is the chat open
      // is the user in chat
      // is the user not blocked PENDING
      return ($this->open and $this->isUser(auth()->user()))?
      true:false;
    }

    public function delete()
    {
        // delete all related messages
        foreach ($this->users as $user) {
          $this->quitUser($user);
        }
        // $this->messages()->delete();
        return parent::delete();
    }


}
