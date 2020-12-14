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
    protected $appends = ['otherUser','lastMessage','currentHaveBlocked'];

    protected $casts = [
        "open" => "boolean"
    ];

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
      return $this->belongsToMany('App\User', 'chats_users')->withPivot('blocked','hidden')->without(['plans','notifications','plans']);
    }

    // the last message sended in the chat
    public function getLastMessageAttribute()
    {
      return $this->messages()
                  ->with('user')
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

    public function getCurrentHaveBlockedAttribute()
    {
      if($this->otherUser)
        return ($this->otherUser->pivot->blocked)? true:false;
      return false;
    }

    public static function giveMeorCreateChatWith($user)
    {
      $chats = $user->allChats;
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
        $this->open = !$bool;
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
      $this->messages()->delete();
      return parent::delete();
    }

    public  static function tabletate($data = null) {
      return [
        'headers' => [
          'Identificador' =>  'name',
          'Usuarios' => [
            'model_name' => 'user',
            'select'     => User::all(), // data al seleccionar en crear
            'show'       => 'name',
            'multiple'   => true,
            'url'        => "admin/user/edit"
          ],
        ],
        'data'  =>  $data,
        'options' => [
          'edit'    => true,
          'add'     => true,
          'remove'  => true,
          'image'   => false,
          'images'  => false,
        ],
        'singular' => 'chat',
        'name'  => 'Chats',

      ];

    }

}
