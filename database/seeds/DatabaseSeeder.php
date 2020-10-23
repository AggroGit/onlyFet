<?php

use Illuminate\Database\Seeder;
use App\Message;
use App\User;
use App\Chat;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        // user admin
        $new = new User;
        $new->name = "Alex";
        $new->email = "admin@gmail.com";
        $new->nickname = "alex1";
        $new->password = bcrypt("123456789");
        $new->save();
        // user admin
        $new2 = new User;
        $new2->name = "Juan";
        $new2->nickname = "juan1";
        $new2->email = "admin2@gmail.com";
        $new2->password = bcrypt("123456789");
        $new2->save();
        // chat
        $chat = new Chat();
        $chat->save();
        // add users
        $chat->addUser($new2);
        $chat->addUser($new);
        // a message
        $message = new Message([
          "user_id" => $new->id,
          "message" => "Hola caracola!",
          "chat_id" => $chat->id,

        ]);
        $message->save();

        // finally we call the passport
        Artisan::call('passport:install');
    }
}
