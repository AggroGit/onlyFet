<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <!-- <meta name="csrf-token" id="token" content="{{ csrf_token() }}"> -->

  <title>OnlyFet</title>

  <script src="https://js.stripe.com/v3/"></script>
  <!-- Scripts -->
  @if(!isset($noTypeScript))
  <script src="{{ asset('js/app.js') }}" defer></script>
  @endif

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <!-- Base -->
  <link href="{{ asset('css/base.css') }}" rel="stylesheet">
  <!-- Own Styles -->

</head>
<body>
    <app :app_code="{{env('APP_ID')}}" id="app"></app>
</body>
</html>
