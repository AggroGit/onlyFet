<?php

namespace App\Navy;



class Enterprise
{
    public static function saludar()
    {
        echo "hola";
    }

    public static function all()
    {
        self::noAuth();
        self::chats();
        self::auth();
    }

    public static function chats()
    {
        require 'routes/chats.php';
    }

    // rutas de usuario
    public static function auth()
    {
      require 'routes/auth.php';
    }

    public static function noAuth()
    {
      require 'routes/no_auth.php';
    }

    public static function stripe()
    {
      require 'routes/stripe.php';
    }

    // routes of the backoffice
    public static function admin()
    {
        require 'routes/admin.php';
    }

    public static function publications()
    {
       require 'routes/publications.php';
    }


}
