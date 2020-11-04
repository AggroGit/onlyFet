<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
    | any of the connections defined in the "connections" array below.
    |
    | Supported: "pusher", "redis", "log", "null"
    |
    */

    'default' => env('BROADCAST_DRIVER', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over websockets. Samples of
    | each available type of connection are provided inside this array.
    |
    */

    'connections' => [

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'enable_client_messages' => true,
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            // 'options' => [
            //     // 'cluster' => env('PUSHER_APP_CLUSTER'),
            //     // 'useTLS' => true,
            //     'cluster' => env('PUSHER_APP_CLUSTER'),
            //     'encrypted' => true,
            //     'host' => env('SOCKETS_IP','82.223.216.96'),
            //     'port' => 443,
            //     'useTLS' => true,
            //     'scheme' => 'https',
            //     'enable_client_messages' => true,
            //
            // ],
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'host' => 'socket.example.com',
                'port' => 443,
                'scheme' => 'https',
                'curl_options' => [
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
