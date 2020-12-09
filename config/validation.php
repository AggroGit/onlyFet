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
      'media.*'    => 'image:mimes:jpg,jpeg,png,video:mov,',
      'content'   => 'required|string',



    ],

    'addComment' => [
      'comment' => 'required|string'
    ],

    'makePremium' => [
      'influencer'  => 'boolean',
      'suscriptions'  => 'array',
      'month1'  => 'required|digits_between:1,400',
      'month3'  => 'required|digits_between:1,400',
      'month6'  => 'required|digits_between:1,400',
      'month12' => 'required|digits_between:1,400',
    ],

    'chooseOrder' => [
      'orderBy' => 'string|in:populars,news,mySuscriptions'
    ],

    'propina' => [
      'quantity'    => 'required|digits_between:1,400',
      'message' =>  'string|nullable'
    ],

    'createAuction' => [
      'media.*'       => 'image:mimes:jpg,jpeg,png,video:mov,',
      'description'   => 'required|string',
      'title'         => 'required|string',
      'price'         => 'required|integer',
      'finish_at'     => 'required',

    ],
    'uploadImage' => [
      'file'       => 'required|image:mimes:jpg,jpeg,png,video:mov,'
    ],
    'searchProducts' => [
      'orderBy' => 'string',
      'categories'  => 'array',
      'search'  => 'string',

    ],

    'bidup' => [
      'price'         => 'required|integer',
    ],






];
