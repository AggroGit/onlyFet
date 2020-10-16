<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validations
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    'register' => [
          'email'     => 'required|string|email|unique:users',
          'password'  => 'required|string',
          'name'      => 'required|string'
    ],

    'register_rrss' => [
          'social_token'      => 'required|string',
          'social_user_name'  => 'required|string',
          'social_user_email' => 'required|string|email',
    ],

    'resetPass'   => [
      'email'  => 'required|email'
    ],

    'login' => [
          'email'     => 'required|string|email',
          'password'  => 'required|string',
    ],

    'login_rrss' => [
          'social_token'      => 'required|string',
    ],

    'sendChat' => [
          'message'   => 'required|string',
          'image'     => 'image:mimes:jpg,jpeg,png'
    ],

    'pay' => [
          'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/'
    ],

    'editAuth' => [
      'name'          => 'nullable|string',
      'email'         => 'nullable|string|email|unique:users',
      'phone_number'  => 'nullable|string',
      'password'      => 'nullable|string',
      'image'     => 'image:mimes:jpg,jpeg,png'
    ],

    'addVisa' => [
      'id'    => 'required|string'
    ],

    'addPost' => [
      'image'     => 'image:mimes:jpg,jpeg,png',
      'content'   => 'required|string',
      'hastags'   => 'array',
        'hastags.*'   =>  'required|string',


    ],

    'addComment' => [
      'comment' => 'required|string'
    ]





];
